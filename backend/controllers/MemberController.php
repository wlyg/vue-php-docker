<?php

namespace app\controllers;

use Yii;
use app\utils\CurlUtil;
use app\models\Member;
use yii\web\ServerErrorHttpException;
use app\utils\MongodbUtil;

class MemberController extends BaseController
{
    public function actionGet()
    {
        $params = Yii::$app->request->get();

        $member = Member::search($params);
        if(!$member){
            return [
                'code' => 300,
                'message' => 'noData'
            ];
        }

        return [
            'code' => 200,
            'data' => $member
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
        $totalRecord = Member::find()->count();
        $record = Member::find()->offset(($currentPage - 1) * $pageSize)->limit($pageSize)->orderBy('createdAt DESC')->all();
        foreach ($record as $item) {
            $item['createdAt'] = MongodbUtil::MongoDate2String($item['createdAt']);
        }

        return [
            'code' => 200,
            'data' => [
                'record' => $record,
                'count' => $totalRecord
            ],
            'msg' => 'success'
        ];
    }
}
