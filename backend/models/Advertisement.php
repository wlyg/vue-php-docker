<?php

namespace app\models;

class Advertisement extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'advertisement';
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['adImg']
        );
    }
}