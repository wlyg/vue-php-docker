<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use app\utils\MongodbUtil;
use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;
use yii\data\Pagination;

class JobController extends BaseController
{
    public function actionTestJob()
    {
        return Yii::$app->resque->createJob('queue_test', 'TestJob', $args = []);
    }
}
