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
use app\models\ParkingPay;
use app\models\Schedule;
use yii\web\ServerErrorHttpException;
use app\utils\MongodbUtil;

class ParkingPayController extends BaseController
{
    const PARKAPI_APPID_TRADE = "217";
    const PARKAPI_KEY_TRADE = "4643ce2bd9b147288b772b7518dbcc43";
    const PARKAPI_APPID_CENTER = "218";
    const PARKAPI_KEY_CENTER = "a2fd76dea80a49b9a51f823e2bd1849c";
    const PARKAPI_URL = "http://pay1.keytop.cn:8099/";

    public function actionGet()
    {
        $params = Yii::$app->request->get();

        $parkingPay = ParkingPay::search($params);
        if(!$parkingPay){
            return [
                'code' => 300,
                'message' => 'noData'
            ];
        }

        return [
            'code' => 200,
            'data' => $parkingPay
        ];
    }

    /**
     * 分页，搜索
     */
    public function actionGetRecordList()
    {
        $request = Yii::$app->request;
        $currentPage = $request->get("currentPage");
        $pageSize = $request->get("pageSize");
        $totalRecord = ParkingPay::find()->count();
        $record = ParkingPay::find()->offset(($currentPage - 1) * $pageSize)->limit($pageSize)->orderBy('inTime DESC')->all();

        return [
            'code' => 200,
            'data' => [
                'record' => $record,
                'count' => $totalRecord
            ],
            'msg' => 'success'
        ];
    }

    /**
     * 同步数据
     */
    public function actionUpdateParkRecord()
    {
        $this->updateCenterRecord();
        $this->updateTradeRecord();
    }

    /**
     * 更新中心停车场数据
     */
    public function updateCenterRecord()
    {
        $schedule = Schedule::findOne();
        $pageIndex =  $schedule['centerIndex'];
        if (!$pageIndex) {
            $pageIndex = 1;
        }
        $appid = self::PARKAPI_APPID_CENTER;
        $key = self::PARKAPI_KEY_CENTER;
        $parkLotInfo = [
            'parkId' => 119,
            'parkName' => '武汉新世界中心停车场'
        ];
        $count = 0;
        $isFinished = false;
        while (!$isFinished) {
            $parkingCarInfoList = $this->getParkingcarInfo($appid, $key, $parkLotInfo, $pageIndex);
            if ($parkingCarInfoList) {
                foreach ($parkingCarInfoList as $parkingCarInfo) {
                    ParkingPay::upsert($parkingCarInfo);
                    $count ++;
                }
                $pageIndex ++;
                Schedule::upsertCenterIndex($pageIndex);
            } else {
                $isFinished = true;
            }
        }
        Yii::warning('updated(center):'.$count.'-records.');
    }

    /**
     * 更新国贸停车场数据
     */
    public function updateTradeRecord()
    {
        $schedule = Schedule::findOne();
        $pageIndex =  $schedule['tradeIndex'];
        if (!$pageIndex) {
            $pageIndex = 1;
        }
        $appid = self::PARKAPI_APPID_TRADE;
        $key = self::PARKAPI_KEY_TRADE;
        $parkLotInfo = [
            'parkId' => 172,
            'parkName' => '武汉新世界国贸'
        ];
        $count = 0;
        $isFinished = false;
        while (!$isFinished) {
            $parkingCarInfoList = $this->getParkingcarInfo($appid, $key, $parkLotInfo, $pageIndex);
            if ($parkingCarInfoList) {
                foreach ($parkingCarInfoList as $parkingCarInfo) {
                    ParkingPay::upsert($parkingCarInfo);
                    $count ++;
                }
                $pageIndex ++;
                Schedule::upsertTradeIndex($pageIndex);
            } else {
                $isFinished = true;
            }
        }
        Yii::warning('updated(trade):'.$count.'-records.');
    }


    /**
     * 获取指定停车场的停车信息
     */
    public function getParkingcarInfo($appid, $key, $parkLotInfo, $pageIndex)
    {
        $pageSize = 20;
        $parkapi_url = self::PARKAPI_URL;
        $parkapi_appid = $appid;
        $parkapi_key = $key;
        $url = $parkapi_url.'api/wec/CarParkingInfo';
        $parkingCarInfoList = array();
        $sign_arr = [$parkLotInfo['parkId'], $pageIndex, $pageSize];
        $sign_str = implode("", $sign_arr);
        $gen_date = date("Ymd");
        $postfix  = $gen_date.$parkapi_key;
        $sign_key = md5($sign_str.$postfix);
        $params = [
            'appId' => $parkapi_appid,
            'key' => $sign_key,
            'parkId' => $parkLotInfo['parkId'],
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize
        ];
        $result = CurlUtil::post($url, $params);
        if (!empty($result['data'])) {
            foreach ($result['data'] as $parkCarInfo) {
                array_push($parkingCarInfoList,
                    array_merge($parkLotInfo, [
                        'plateNo' => $parkCarInfo['plateNo'],
                        'inTime' => $parkCarInfo['inTime'],
                        'outTime' => $parkCarInfo['outTime'],
                        'parkLong' => $parkCarInfo['parkLong']
                    ])
                );
            }
        }

        return $parkingCarInfoList;
    }

    public function actionDelete()
    {
        return ParkingPay::deleteAll([]);
    }
}
