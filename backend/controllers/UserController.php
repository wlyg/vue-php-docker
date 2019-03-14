<?php

namespace app\controllers;

use Yii;
use app\utils\CurlUtil;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;
use app\models\User;
use app\models\Config;
use app\models\Member;

class UserController extends BaseController
{
    /**
     * 获取注册短信验证码
     */
    public function actionGetRegistCode()
    {
        $mobile = Yii::$app->request->post("mobile");
        $url = Yii::$app->params['request_url'].'Oauth&act=sendRegisterSMS';
        $params = ['phone' => $mobile];
        $result = CurlUtil::post($url, $params);
        $msg = $result['msg'];
        $code = $result['status'];

        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    /**
     * 获取找回密码验证码
     */
    public function actionGetPwdCode()
    {
        $mobile = Yii::$app->request->post("mobile");
        $url = Yii::$app->params['request_url'].'Oauth&act=send_findpwd_code';
        $params = [
            'login' => $mobile,
            'codeType' => 1
        ];
        $result = CurlUtil::post($url, $params);
        $msg = $result['msg'];
        $code = $result['status'];

        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    /**
     * 新用户注册
     */
    public function actionRegist()
    {
        $name = Yii::$app->request->post("name");
        $password = Yii::$app->request->post("password");
        $password = md5($password);
        $mobile = Yii::$app->request->post("mobile");
        $regCode = Yii::$app->request->post("code");
        $openid = Yii::$app->request->post("openid");

        $url = Yii::$app->params['request_url'].'Oauth&act=register&codeType=1&password='.$password.'&phone='.$mobile.'&regCode='.$regCode.'&uname='.$name;
        $result = CurlUtil::get($url);
        $msg = $result['msg'];
        $code = $result['status'];
        if ($code == 1) {
            $url = Yii::$app->params['request_url'].'Oauth&act=authorize&passwd='.$password.'&uid='.$mobile;
            $result = CurlUtil::get($url);
            if (isset($result['uid']) && isset($result['oauth_token']) && isset($result['oauth_token_secret'])) {
                $oauth_token = $result['oauth_token'];
                $oauth_token_secret = $result['oauth_token_secret'];
                $userId = $result['uid'];
                $this->saveUserInfo($userId, $oauth_token, $oauth_token_secret);
            }
        }

        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    /**
     * 保存用户信息
     */
    public function saveUserInfo($userId, $oauth_token, $oauth_token_secret)
    {
        $url = Yii::$app->params['request_url'].'User&act=show';
        $params = [
            'user_id' => $userId,
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret
        ];
        $userInfo = CurlUtil::post($url, $params);
        Member::upsert($userInfo);
    }

    /**
     * 修改密码
     */
    public function actionChangePassword()
    {
        $password = Yii::$app->request->post("password");
        $password = md5($password);
        $mobile = Yii::$app->request->post("mobile");
        $regCode = Yii::$app->request->post("code");

        $url = Yii::$app->params['request_url'].'Oauth&act=save_user_pwd&code='.$regCode.'&codeType=1&phone='.$mobile.'&pwd='.$password;
        $result = CurlUtil::get($url);
        $msg = $result['msg'];
        $code = $result['status'];

        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    /**
     * 用户登录
     */
    public function actionLogin()
    {
        $config = Config::findOne();
        if ($config['switch'] === 'false') {
            return [
                'code' => 0,
                'data' => [],
                'msg' => '登录失败'
            ];
        }

        $passwdType = Yii::$app->request->post("passwdType");
        $uid = Yii::$app->request->post("uid");
        $openid = Yii::$app->request->post("openid");
        $passwd = Yii::$app->request->post("passwd");
        if ($passwdType != 'encryption') {
            $passwd = md5($passwd);
        }
        $url = Yii::$app->params['request_url'].'Oauth&act=authorize&passwd='.$passwd.'&uid='.$uid;
        $result = CurlUtil::get($url);
        $status = isset($result['status']) ? $result['status'] : 200;
        $code = 0;
        $msg = '';
        $data = null;
        if ($status == 403) {
            $code = 0;
            $msg = '用户名与密码不匹配';
        } else if ($status == 0){
            $code = 0;
            $msg = $result['message'];
        } else if ($status == 200) {
            $code = 1;
            $msg = '登录成功';
            $data = $result;
            User::upsert($openid, $uid, $passwd);
        }

        return [
            'code' => $code,
            'data' => $data,
            'msg' => $msg
        ];
    }

    /**
     * 用户退出
     */
    public function actionLogout()
    {
        $openid = Yii::$app->request->post("openid");
        $user = User::findOne(['openid' => $openid]);
        $user->delete();
    }

    /**
     * 根据openid查询用户
     */
    public function actionGetuserByOpenid()
    {
        $openid = Yii::$app->request->post("openid");
        $user = User::findOne(['openid' => $openid]);
        $code = 0;
        $data = null;
        if ($user != null) {
            $code = 1;
            $data = [
                'mobile' => $user->mobile,
                'password' => $user->password
            ];
        } else {
            $code = 0;
        }

        return [
            'code' => $code,
            'data' => $data,
            'msg' => 'success'
        ];
    }

    /**
     * 检查用户是否通过认证
     */
    public function actionCheckUserConstraint()
    {
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $url = Yii::$app->params['request_url'].'Entrance&act=checkUserInfoConstraint&oauth_token='.$oauth_token.'&oauth_token_secret='.$oauth_token_secret.'&scene=2';
        $result = CurlUtil::get($url);
        $status = $result['status'];
        if ($status == 1) {
			$msg = '审核已经通过';
		} else if ($status == 2) {
			$msg = '请完善审核所需资料';
		} else if ($status == 4) {
			$msg  = '资料被驳回，请完善所需资料';
		} else {
			$msg = '正在审核中';
        }

        return [
            'code' => $status,
            'msg' => $msg
        ];
    }

    /**
     * 获取用户个人信息
     */
    public function actionShowUserinfo()
    {
        $user_id = Yii::$app->request->post("user_id");
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $url = Yii::$app->params['request_url'].'User&act=show';
        $params = [
            'user_id' => $user_id,
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret
        ];
        $result = CurlUtil::post($url, $params);

        return $result;
    }

    /**
     * 获取公司地址列表
     */
    public function actionGetAddress()
    {
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $url = Yii::$app->params['request_url'].'User&act=get_cpaddress_area_list&oauth_token='.$oauth_token.'&oauth_token_secret='.$oauth_token_secret;
        $result = CurlUtil::get($url);
        $status = isset($result['status']) ? $result['status'] : '';
        $code = 0;
        $data = null;
        $msg = '';
        if ($status != 403) {
            $code = 1;
            $data = $result;
            $msg = '获取地址列表成功';
        } else {
            $code = 0;
            $msg = '获取地址列表失败';
        }

        return [
            'code' => $code,
            'data' => $data,
            'msg' => $msg
        ];
    }

    /**
     * 提交认证
     */
    public function actionSubmitAuthorize()
    {
        $userId = Yii::$app->request->post("user_id");
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $name = Yii::$app->request->post("name");
        $companyName = Yii::$app->request->post("companyName");
        $department = Yii::$app->request->post("department");
        $building = Yii::$app->request->post("building");
        $floor = Yii::$app->request->post("floor");
        $room = Yii::$app->request->post("room");
        $entryNumber = Yii::$app->request->post("entryNumber");
        $url = Yii::$app->params['request_url'].'User&act=save_user_info';
        $params = [
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret,
            'truename' => $name,
            'cpname' => $companyName,
            'position' => $department,
            'passcard_sno' => $entryNumber,
            'cpaddress_build' => $building,
            'cpaddress_floor' => $floor,
            'cpaddress_number' => $room
        ];
        $result = CurlUtil::post($url, $params);
        $status = $result['status'];
        $msg = $result['msg'];
        $code = 0;
        if ($status == 1) {
            $code = 1;
            $msg = '提交成功';
            $this->saveUserInfo($userId, $oauth_token, $oauth_token_secret);
        } else {
            $code = 0;
        }

        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    /**
     * base64转图片
     */
    public function createImage($base64_image_content){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = Yii::$app->getRuntimePath().'/temp/';
            if(!file_exists($new_file)){
                mkdir($new_file);
                chmod($new_file,0777);
            }
            $new_file = $new_file.time().'.'.$type;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return $new_file;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 上传出入证
     */
    public function actionUploadPasscard()
    {
        $passcard = Yii::$app->request->post("image");
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $filename = $this->createImage($passcard);
        $minetype = 'image/jpeg';
        $curl_file = curl_file_create($filename, $minetype);
        $url = Yii::$app->params['request_url'].'User&act=upload_passcard';
        $params = [
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret,
            'passcard_cp' => $curl_file
        ];
        $result = CurlUtil::post($url, $params);
        if (!unlink($filename)) {
            Yii::warning('删除失败');
        } else {
            Yii::warning('删除成功');
        }
        $status = $result['status'];
        $code = 0;
        $msg = '';
        if ($status == 1) {
            $code = 1;
            $msg = '上传成功';
        } else {
            $code = 0;
            $msg = '上传失败';
        }

        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    /**
     * 获取意见分类
     */
    public function actionGetOptypelist()
    {
        $type = Yii::$app->request->post("type");
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $params = [
            'type' => $type,
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret
        ];
        $url = Yii::$app->params['request_url'].'User&act=getOptypelist';
        $result = CurlUtil::post($url, $params);
        $status = isset($result['status']) ? $result['status'] : '';
        $code = 0;
        $msg = '';
        $data = null;
        if ($status != 403) {
            $code = 1;
            $msg = '成功获取意见分类';
            $data = $result;
        } else {
            $code = 0;
            $msg = '获取意见分类失败';
        }

        return [
            'code' => $code,
            'data' => $data,
            'msg' => $msg
        ];
    }

    /**
     * 提交意见
     */
    public function actionSubmitOpinion()
    {
        $content = Yii::$app->request->post("content");
        $tid = Yii::$app->request->post("tid");
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $params = [
            'tid' => $tid,
            'content' => $content,
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret
        ];
        $url = Yii::$app->params['request_url'].'User&act=addop';
        $result = CurlUtil::post($url, $params);
        $status = $result['status'];
        $code = 0;
        $msg = '';
        if ($status == 1) {
            $code = 1;
            $msg = '问题反馈提交成功';
        } else {
            $code = 0;
            $msg = '问题反馈提交失败';
        }

        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    public function actionCards()
    {
        $uid = Yii::$app->request->post("uid");
        $passwd = Yii::$app->request->post("passwd");
        $passwd = md5($passwd);
        $url = Yii::$app->params['request_url'].'Oauth&act=authorize&passwd='.$passwd.'&uid='.$uid;
        $result = CurlUtil::get($url);

        return $result;
    }
}
