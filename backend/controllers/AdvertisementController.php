<?php

namespace app\controllers;

use app\models\Advertisement;

class AdvertisementController extends BaseController
{
    /**
     * For h5 page
     */
    public function actionIndex()
    {
        $advertisement = Advertisement::findOne();

        return ['code' => 200, 'data' => $advertisement, 'message' => 'success'];
    }
}
