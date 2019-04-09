<?php

namespace app\controllers;

use Yii;
use app\utils\CurlUtil;
use app\utils\StringUtil;
use yii\web\BadRequestHttpException;

class KidsplaceController extends BaseController
{
    public function actionTest()
    {
        return [
            'code' => 200,
            'data' => 'hello',
            'message' => 'success'
        ];
    }
}
