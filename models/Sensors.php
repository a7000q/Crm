<?php

namespace app\models;

use Yii;
use app\models\SmsCenter;
use app\models\SensorMonitors;

/**
 * This is the model class for table "sensors".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_fuel_module_section
 */
class Sensors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sensors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id_fuel_module_section'], 'required'],
            [['id_fuel_module_section', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sms'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'id_fuel_module_section' => 'Id Fuel Module Section',
        ];
    }

    public function getFuelModuleSection()
    {
        return $this->hasOne(FuelModuleSections::className(), ['id' => 'id_fuel_module_section']);
    }

    public function runStatus()
    {
        $now = time();
        $phone = "89600506123";

        $diff_time = $now - $this->status;

        if ($diff_time > 300)
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
        $txt = "Сенсор ".$this->name." на топливном модуле ".$this->fuelModuleSection->module->name." недоступен";

        return $txt;
    }

    public function getMessageStatusConnect()
    {
        $txt = "Сенсор ".$this->name." на топливном модуле ".$this->fuelModuleSection->module->name." доступен";

        return $txt;
    }

    public function getMonitors($date1, $date2)
    {
        return SensorMonitors::find()->where(['id_sensor' => $this->id])->andWhere('date >= :date1', [':date1' => $date1])->andWhere('date <= :date2', [':date2' => $date2])->asArray()->all();
    }

    public function getMonitorByDate($date)
    {
        $result = SensorMonitors::find()->where(['id_sensor' => $this->id])->andWhere('date >= :date', [':date' => $date])->andWhere('date <= :date2', [':date2' => $date+20])->orderBy('date')->one();

        if ($result)
            $result = $result->fuel_level;
        else
            $result = 0;

        return $result;
    }
}
