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

class EntranceController extends BaseController
{
    /**
     *获取门禁code
     */
    public function actionGetEntranceCode()
    {
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $url = Yii::$app->params['request_url'].'Entrance&act=getCode&oauth_token='.$oauth_token.'&oauth_token_secret='.$oauth_token_secret;
        $result = CurlUtil::get($url);

        return $result;
    }

    /**
     *检查门禁二维码有效性
     */
    public function actionCheckEntranceCode()
    {
        $oauth_token = Yii::$app->request->post("oauth_token");
        $oauth_token_secret = Yii::$app->request->post("oauth_token_secret");
        $entranceCode = Yii::$app->request->post("entranceCode");
        $url = Yii::$app->params['request_url'].'Entrance&act=checkCode&code='.$entranceCode.'&oauth_token='.$oauth_token.'&oauth_token_secret='.$oauth_token_secret;
        $result = CurlUtil::get($url);

        return $result;
    }
}
