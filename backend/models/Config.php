<?php

namespace app\models;

use app\utils\MongodbUtil;

class Config extends BaseModel
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'config';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['appid', 'secret', 'accessToken', 'accessTokenTime', 'jsTicket', 'jsTicketTime', 'switch']
        );
    }

    public function fields()
    {
        return array_merge(
            parent::attributes(),
            [
                'appid', 'secret', 'accessToken', 'accessTokenTime', 'switch',
                'createdAt' => function () {
                    return MongodbUtil::MongoDate2String($this->createdAt);
                },
                'updatedAt' => function () {
                    return MongodbUtil::MongoDate2String($this->updatedAt);
                }
            ]
        );
    }

    public static function upsert($appid, $secret)
    {
        $config = self::findOne();
        if (empty($config)) {
            $config = new self();
        }
        $config->appid = $appid;
        $config->secret = $secret;
        $config->accessToken = '';
        $config->jsTicket = '';
        $config->accessTokenTime = '';
        $config->jsTicketTime = '';

        return $config->save();
    }

    public static function updateSwitch($switch)
    {
        $config = self::findOne();
        if (empty($config)) {
            $config = new self();
        }

        $config->switch = $switch;

        return $config->save();
    }
}
