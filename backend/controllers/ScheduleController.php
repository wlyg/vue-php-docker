<?php

namespace app\controllers;

use Yii;
use app\utils\CurlUtil;
use app\utils\StringUtil;
use app\models\Schedule;
use yii\web\BadRequestHttpException;

class ScheduleController extends BaseController
{
    public function actionSave()
    {
        $params = Yii::$app->request->post();
        if (empty($params['centerIndex']) && empty($params['tradeIndex'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $result = Schedule::upsert($params['tradeIndex'], $params['centerIndex']);

        return ['code' => 200, 'data' => $result, 'message' => 'success'];
    }

    public function actionView()
    {
        return Schedule::find()->all();
    }

    public function actionDelete()
    {
        return Schedule::deleteAll([]);
    }
}
