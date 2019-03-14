<?php

namespace app\controllers;

use Yii;
use app\models\CustomerService;
use yii\web\ServerErrorHttpException;
use app\utils\MongodbUtil;

class CustomerServiceController extends BaseController
{
    public function actionGet()
    {
        $customerService = CustomerService::findOne();
        if ( !$customerService ) {
            return [
                'code' => 200,
                'message' => 'failed'
            ];
        }
        return [
            'code' => 200,
            'data' => $customerService,
            'message' => 'success'
        ];
    }

    public function actionSave()
    {
        $params = Yii::$app->request->post();
        if (empty($params['routineItem'])) {
            throw new BadRequestHttpException('miss parameter');
        }

        $customerService = CustomerService::findOne();
        if ( !$customerService ) {
            $customerService = new CustomerService;
        }
        foreach($params['specialItem'] as $index => $item){
          $params['specialItem'][$index]['date'] = MongodbUtil::convertToMongoDate($params['specialItem'][$index]['date']);
        }

        $customerService->routineItem = $params['routineItem'];
        $customerService->specialItem = $params['specialItem'];
        $customerService->phone = $params['phone'];
        $customerService->entrancePhone = $params['entrancePhone'];

        if (!$customerService->save()) {
            throw new ServerErrorHttpException('save CustomerService failed');
        }
        return [
          'code' => 200,
          'message' => 'ok'
        ];
    }

    /**
     * For h5 page
     *
     * 判断当前时间是否为工作时间
     */
    public function actionVerify()
    {
        $isWork = false;
        $phone = '';
        $entrancePhone = '';

        $customerService = CustomerService::findOne();

        if (!empty($customerService)) {
            $currentDate = date('Y-m-d');
            $currentDateTime = MongodbUtil::convertToMongoDate(strtotime($currentDate));
            $specialItems = $customerService->specialItem;
            $specialItem = $this->getSpecialItem($specialItems, $currentDateTime);

            $time = [];
            if (!empty($specialItem)) {
                $time = $specialItem['time'];
            } else {
                $currentWeek = CustomerService::WEEK_MAP[date('w')];
                $time = $customerService->routineItem[$currentWeek];
            }

            $isWork = $this->verifyTime($time);
            $phone = $customerService->phone;
            $entrancePhone = $customerService->entrancePhone;
        }

        return [
            'code' => 200,
            'data' => [
                'isWork' => $isWork,
                'phone' => $phone,
                'entrancePhone' => $entrancePhone
            ],
            'message' => 'ok'
        ];
    }

    private function getSpecialItem($specialItems, $currentDateTime)
    {
        $result = null;
        foreach ($specialItems as $item) {
            $targetTime = MongodbUtil::MongoDate2msTimeStamp($item['date']);
            $currentDateTime = MongodbUtil::MongoDate2msTimeStamp($currentDateTime);
            if ($targetTime === $currentDateTime) {
                $result = $item;
                break;
            }
        }

        return $result;
    }

    private function verifyTime($time)
    {
        if ($time === false) {
            return false;
        }

        $from = str_replace(':', '', $time[0]);
        $to = str_replace(':', '', $time[1]);
        // 获取当前时间的小时和分钟
        $current = date('Hi');

        if ($current > $from && $current < $to) {
            return true;
        }

        return false;
    }
}
