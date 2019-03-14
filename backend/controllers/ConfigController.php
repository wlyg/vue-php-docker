<?php

namespace app\controllers;

use Yii;
use app\utils\CurlUtil;
use app\utils\StringUtil;
use app\models\Config;
use yii\web\BadRequestHttpException;

class ConfigController extends BaseController
{
    public function actionSaveConfig()
    {
        $params = Yii::$app->request->post();
        if (!isset($params['appid']) && !isset($params['secret'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $result = Config::upsert($params['appid'], $params['secret']);

        return [
            'code' => 200,
            'data' => $result,
            'message' => 'success'
        ];
    }

    public function actionGetConfig()
    {
        $config = Config::findOne();

        return [
            'code' => 200,
            'data' => $config,
            'message' => 'success'
        ];
    }

    public function actionUpdateSwitch()
    {
        $params = Yii::$app->request->get();
        if (!isset($params['switch'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $result = Config::updateSwitch($params['switch']);

        return [
            'code' => 200,
            'data' => $result,
            'message' => 'success'
        ];
    }
}
