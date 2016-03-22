<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_calibr".
 *
 * @property integer $id
 * @property integer $date
 * @property double $litr
 * @property integer $h
 * @property integer $density
 */
class TestCalibr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_calibr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'litr', 'h', 'density'], 'required'],
            [['date'], 'integer'],
            [['litr', 'h', 'density'], 'number'],
            ['date', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'litr' => 'Litr',
            'h' => 'H',
            'density' => 'Density',
        ];
    }

    public static function minH($h)
    {   
        return static::find()->where(["<=", "h", $h])->orderBy(["date" => SORT_ASC])->one();
    }

    public static function maxH($h)
    {
        return static::find()->where([">=", "h", $h])->orderBy(["date" => SORT_DESC])->one();
    }

    public function getCoordsLitr()
    {
        return $this->litr;
    }

    public static function getLitrByH($h)
    {
        $minH = static::minH($h);
        $maxH = static::maxH($h);

        $litr = (($maxH->coordsLitr - $minH->coordsLitr)/($maxH->h - $minH->h))*($h - $minH->h)+$minH->coordsLitr;

        return $litr;
    }
}
