<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bad_tranzactions".
 *
 * @property integer $id
 * @property string $id_electro
 * @property integer $id_terminal
 * @property integer $id_error
 * @property integer $date
 * @property string $doza
 */
class BadTranzactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bad_tranzactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_electro', 'id_terminal', 'id_error', 'date'], 'required'],
            [['id_terminal', 'id_error', 'date'], 'integer'],
            [['id_electro'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_electro' => 'Id Electro',
            'id_terminal' => 'Id Terminal',
            'id_error' => 'Id Error',
            'date' => 'Date',
        ];
    }
}
