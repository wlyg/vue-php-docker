<?php

namespace app\models;

use app\utils\MongodbUtil;

class LeavePhone extends BaseModel
{
    const STATUS_RESOLVED = 'resolved';
    const STATUS_TO_BE_SOLVED = 'toBeSolved';

    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'leavePhone';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['openid', 'phone', 'leaveTime', 'projectName', 'status', 'remark']
        );
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['status', 'default', 'value' => self::STATUS_TO_BE_SOLVED]
            ]
        );
    }

    public function fields()
    {
        return array_merge(
            parent::attributes(),
            [
                'openid', 'phone', 'leaveTime', 'projectName', 'status', 'remark',
                'leaveTime' => function () {
                    return MongodbUtil::MongoDate2String($this->leaveTime);
                },
                'createdAt' => function () {
                    return MongodbUtil::MongoDate2String($this->createdAt);
                },
                'updatedAt' => function () {
                    return MongodbUtil::MongoDate2String($this->updatedAt);
                }
            ]
        );
    }
}
