<?php

namespace app\models;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;

class Member extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'member';
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['trueName' , 'uName', 'uid', 'phone', 'avatar', 'sex', 'age', 'email', 'position', 'cpName', 'cpAddress']
        );
    }

    public static function search($params)
    {
        $query = new Query();
        $query->from('member')->where(['isDeleted' => self::NOT_DELETED]);
        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $members = $provider->getModels();
        return $members;
    }

    public static function upsert($userInfo)
    {
        $member = Member::find()->where(['uid' =>$userInfo['uid']])->one();
        if (empty($member)) {
            $member = new self();
        }
        $member->trueName = $userInfo['truename'];
        $member->avatar = $userInfo['avatar'];
        $member->age = $userInfo['age'];
        $member->uName = $userInfo['uname'];
        $member->sex = $userInfo['sex'];
        $member->email = $userInfo['email'];
        $member->phone = $userInfo['phone'];
        $member->cpName = $userInfo['cpname'];
        $member->cpAddress = $userInfo['cpaddress'];
        $member->uid = $userInfo['uid'];
        $member->position = $userInfo['position'];

        return $member->save();
    }
}