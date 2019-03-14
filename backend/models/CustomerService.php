<?php

namespace app\models;

class CustomerService extends BaseModel
{

    // 星期几的数字表示（0 表示 Sunday[星期日] ，6 表示 Saturday[星期六] ）
    const WEEK_MAP = ['sun', 'mon', 'tues', 'wed', 'thur', 'fri', 'sat'];

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'customerService';
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['routineItem', 'specialItem', 'phone', 'entrancePhone']
        );
    }
}
