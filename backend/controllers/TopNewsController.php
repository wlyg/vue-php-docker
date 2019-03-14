<?php

namespace app\controllers;

use app\models\TopNews;

class TopNewsController extends BaseController
{
    /**
     * For h5 page
     */
    public function actionIndex()
    {
        $orderBy['createdAt'] = SORT_ASC;
        $newsList = TopNews::find()->where(['isDeleted' => TopNews::NOT_DELETED])->orderBy($orderBy)->all();

        return [
            'code' => 200,
            'data' => $newsList,
            'message' => 'success'
        ];
    }
}
