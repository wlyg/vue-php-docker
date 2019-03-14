<?php

namespace app\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\helpers\ArrayHelper;
use MongoDB\BSON\UTCDateTime;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;


class Project extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return 'project';
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['name', 'code', 'averagePrice', 'connect', 'location', 'position', 'type', 'coverImg', 'panoramaImg', 'infoImg', 'houseImg', 'characteristic', 'houseType', 'traffic', 'facilities', 'periphery', 'award', 'fixedYear', 'makeHouse', 'parking', 'green', 'designerImg', 'isPublished', 'phone']
        );
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['isPublished', 'default', 'value' => false]
            ]
        );
    }

    public static function search($params)
    {
        $query = new Query();
        $query->from('project')->where(['isDeleted' => self::NOT_DELETED]);
        $orderBy['code'] = SORT_ASC;
        $query = $query->orderBy($orderBy);
        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $projects = $provider->getModels();
        return $projects;
    }
}