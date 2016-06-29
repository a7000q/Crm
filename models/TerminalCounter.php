<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "terminal_counter".
 *
 * @property integer $id
 * @property integer $id_terminal
 * @property integer $id_tranzaction
 * @property integer $date
 * @property double $sumLitr
 */
class TerminalCounter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminal_counter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_terminal', 'id_tranzaction', 'date', 'sumLitr'], 'required'],
            [['id_terminal', 'id_tranzaction', 'date'], 'integer'],
            [['sumLitr'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_terminal' => 'Id Terminal',
            'id_tranzaction' => 'Id Tranzaction',
            'date' => 'Date',
            'sumLitr' => 'Sum Litr',
        ];
    }
}
