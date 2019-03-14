<?php

namespace app\models;

class Schedule extends BaseModel
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'schedule';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['tradeIndex', 'centerIndex']
        );
    }

    public static function upsert($tradeIndex, $centerIndex)
    {
        $schedule = self::findOne();
        if (empty($schedule)) {
            $schedule = new self();
        }

        $schedule->tradeIndex = $tradeIndex;
        $schedule->centerIndex = $centerIndex;

        return $schedule->save();
    }

    public static function upsertTradeIndex($tradeIndex)
    {
        $schedule = self::findOne();
        if (empty($schedule)) {
            $schedule = new self();
        }

        $schedule->tradeIndex = $tradeIndex;

        return $schedule->save();
    }

    public static function upsertCenterIndex($centerIndex)
    {
        $schedule = self::findOne();
        if (empty($schedule)) {
            $schedule = new self();
        }

        $schedule->centerIndex = $centerIndex;

        return $schedule->save();
    }
}
