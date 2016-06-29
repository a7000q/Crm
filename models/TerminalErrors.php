<?php

namespace app\models;

use Yii;
use app\models\SmsCenter;

/**
 * This is the model class for table "terminal_errors".
 *
 * @property integer $id
 * @property integer $id_terminal
 * @property integer $date
 * @property string $text
 */
class TerminalErrors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminal_errors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_terminal', 'date', 'text'], 'required'],
            [['id_terminal', 'date'], 'integer'],
            [['text'], 'string'],
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
            'date' => 'Date',
            'text' => 'Text',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) 
        {
           //$this->sendSms(); 
           return true;
        } 
        else 
        {
            return false;
        }
    } 

    public function sendSms()
    {
        $msg = "Ошибка на т. модуле: ".$this->terminal->fuelModule->name." Текст: ".$this->text;
        $msg = substr($msg, 0, 60);
        $SmsCenter = new SmsCenter();
        $SmsCenter->send("89600506123", $msg);
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminals::className(), ['id' => 'id_terminal']);
    }


    public function getDateText()
    {
        return date("d.m.Y H:i:s", $this->date);
    }
    
}
