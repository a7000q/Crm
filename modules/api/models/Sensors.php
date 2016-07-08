<?php

namespace app\modules\api\models;

use Yii;
use app\models\Terminals;
use app\models\SensorMonitors;

/**
 * This is the model class for table "sensors".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_fuel_module_section
 * @property integer $status
 * @property string $sms
 * @property string $h
 * @property string $density
 * @property string $temp
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
            [['name', 'id_fuel_module_section', 'h', 'density', 'temp', 'water_level'], 'required'],
            [['id_fuel_module_section', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['sms', 'h', 'density', 'temp', 'water_level'], 'string', 'max' => 10],
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
            'status' => 'Status',
            'sms' => 'Sms',
            'h' => 'H',
            'density' => 'Density',
            'temp' => 'Temp',
        ];
    }

    public static function findByTerminal($id_terminal)
    {
        $Terminal = Terminals::findOne($id_terminal);

        if ($Terminal->fuelModule->fuelModuleSections[0]->sensor)
            return self::findOne($Terminal->fuelModule->fuelModuleSections[0]->sensor->id);
    }

    public function fixValue($h, $density, $temp, $water_level)
    {
        $h = str_replace(",", ".", $h);
        $density = str_replace(",", ".", $density);
        $temp = str_replace(",", ".", $temp);
        $water_level = str_replace(",", ".", $water_level);

        $this->h = $h;
        $this->density = $density;
        $this->temp = $temp;
        $this->water_level = (string)$water_level;

        $this->status = time();

        if ($this->validate())
        {
            $r["status"] = 'ok';
            $this->save();
            $this->saveSensorMonitor();
        }
        else
        {
            $r["status"] = "error";
            $r['object'] = $this;
        }

        return $r;
    }

    public function  saveSensorMonitor()
    {
        $sensor_monitor = new SensorMonitors();

        $sensor_monitor->id_sensor = $this->id;
        $sensor_monitor->date = time();
        $sensor_monitor->fuel_level = $this->h;
        $sensor_monitor->temp = $this->temp;
        $sensor_monitor->density = $this->density;
        $sensor_monitor->water_level = (string)$this->water_level;

        $sensor_monitor->save();
    }
}
