<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "real_time_tranzactions".
 *
 * @property integer $id
 * @property integer $id_tranzaction
 * @property integer $date
 * @property string $doza
 */
class RealTimeTranzactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'real_time_tranzactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tranzaction', 'date', 'doza'], 'required'],
            [['id_tranzaction', 'date'], 'integer'],
            [['doza'], 'number'],
            ['status', 'string'],
            ['status', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tranzaction' => 'Id Tranzaction',
            'date' => 'Date',
            'doza' => 'Doza',
        ];
    }
}
