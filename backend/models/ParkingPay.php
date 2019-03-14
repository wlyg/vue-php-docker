<?php

namespace app\models;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;

class ParkingPay extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'parkingPay';
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            [
                'plateNo',
                'parkId',
                'parkName',
                'inTime',
                'outTime',
                'parkLong'
            ]
        );
    }

    public static function search($params)
    {
        $query = new Query();
        $query->from('parkingPay')->where(['isDeleted' => self::NOT_DELETED]);
        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $parkingPays = $provider->getModels();
        return $parkingPays;
    }

    public static function upsert($parkingCarInfo)
    {
        $parkingPay = ParkingPay::find()->where([
            'plateNo' =>$parkingCarInfo['plateNo'],
            'parkId' =>$parkingCarInfo['parkId'],
            'inTime' =>$parkingCarInfo['inTime']
        ])->one();
        if (empty($parkingPay)) {
            $parkingPay = new self();
        }
        $parkingPay->plateNo = $parkingCarInfo['plateNo'];
        $parkingPay->parkId = $parkingCarInfo['parkId'];
        $parkingPay->parkName = $parkingCarInfo['parkName'];
        $parkingPay->inTime = $parkingCarInfo['inTime'];
        $parkingPay->outTime = $parkingCarInfo['outTime'];
        $parkingPay->parkLong = $parkingCarInfo['parkLong'];

        return $parkingPay->save();
    }
}