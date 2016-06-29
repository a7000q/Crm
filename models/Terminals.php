<?php

namespace app\models;

use Yii;
use app\models\FuelModuleSections;
use app\models\SmsCenter;
use app\models\FuelModule;
use yii\Helpers\ArrayHelper;

/**
 * This is the model class for table "terminals".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_fuel_module_section
 */
class Terminals extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id_fuel_module'], 'required'],
            [['id', 'id_fuel_module', 'counter_date'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sms'], 'string', 'max' => 10],
            ['doza', 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'id_fuel_module' => 'Топливный модуль',
            'fuelModule.name' => 'Топливный модуль',
            'status' => 'Статус',
            'lastActivity' => 'Последняя активность',
            'doza' => 'Максимальная доза'
        ];
    }

    public function getFuelModule()
    {
        return $this->hasOne(FuelModule::className(), ['id' => 'id_fuel_module']);
    }

    public function getAvailability()
    {
        if ((time()-$this->counter_date) > 400)
            return false;
        else
            return true;
    }

    public function getStatus()
    {
        return $this->availabilityText;
    }

    public function getAvailabilityText()
    {
        if ($this->availability)
            return "Доступен";
        else
            return "Не доступен";
    }

    public function setVizit()
    {
        $this->counter_date = time();
        $this->save();
    }

    public function runStatus()
    {
        $now = time();
        $phone = "89600506123";

        if (!$this->availability)
        {
            
            $sms = new SmsCenter();

            $msg = $this->messageStatusDisconnect;

            if ($this->sms == "true")
            {
                $sms->send($phone, $msg);
                $this->sms = "false";
                $this->save();
            }
        }
        else if ($this->sms == "false")
        {
            $this->sms = "true";
            $this->save();
            $sms = new SmsCenter();
            $msg = $this->messageStatusConnect;
            $sms->send($phone, $msg);
        }
    }

    public function getMessageStatusDisconnect()
    {
        $txt = $this->fuelModule->name." не доступна";

        return $txt;
    }

    public function getMessageStatusConnect()
    {
        $txt = $this->fuelModule->name." доступна";

        return $txt;
    }

    public function getFuelModules()
    {
        $modules = FuelModule::find()->all();
        $modules = ArrayHelper::map($modules, 'id', 'name');

        return $modules;
    }

    public function getLastActivity()
    {
        return date("d.m.Y H:i:s", $this->counter_date);
    }
}
