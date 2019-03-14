<?php

namespace app\models;

use app\utils\MongodbUtil;

class TopNews extends BaseModel
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'topNews';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['title', 'img', 'newsUrl']
        );
    }

    public function fields()
    {
        return array_merge(
            parent::attributes(),
            [
              'title', 'img', 'newsUrl',
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
