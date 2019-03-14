<?php

namespace app\controllers;

use Yii;
use app\utils\CurlUtil;
use app\utils\StringUtil;
use app\utils\MongodbUtil;
use app\models\LeavePhone;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;

class LeavePhoneController extends BaseController
{
    public function actionSave()
    {
        $params = Yii::$app->request->post();
        if (empty($params['openid']) && empty($params['phone']) && empty($params['projectName'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $leavePhone = new LeavePhone();
        $leavePhone->openid = $params['openid'];
        $leavePhone->phone = $params['phone'];
        $leavePhone->projectName = $params['projectName'];
        $leavePhone->leaveTime = MongodbUtil::convertToMongoDate();
        $result = $leavePhone->save();

        return ['code' => 200, 'data' => $result, 'message' => 'success'];
    }

    public function actionView()
    {
        return LeavePhone::find()->all();
    }

    public function actionGet()
    {
        $query = LeavePhone::find()->where(['isDeleted' => LeavePhone::NOT_DELETED]);
        $count = $query->count();
        $orderBy['leaveTime'] = SORT_DESC;
        $query = $query->orderBy($orderBy);
        $pagination = new Pagination(['totalCount' => $count]);
        $leavePhones = $query->offset($pagination->offset)->limit($pagination->limit)->all();

        return [
            'code' => 200,
            'data' => [
                'items' => $leavePhones,
                'currentPage' => $pagination->getPage(),
                'pageCount' => $pagination->getPageCount(),
                'perPage' => $pagination->getPageSize(),
                'totalCount' => $count

            ],
            'message' => 'success'
        ];
    }

    public function actionEdit()
    {

        $params = Yii::$app->request->post();
        if (empty($params['id']) && empty($params['status'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $leavePhone = LeavePhone::findOne(['_id' => $params['id']]);
        $leavePhone->status = $params['status'];
        $leavePhone->remark = $params['remark'];

        if( !$leavePhone->save() ){
            return [
                'code' => 300,
                'message' => 'failed'
            ];
        }

        return [
            'code' => 200,
            'message' => 'success'
        ];
    }
}
