<?php

namespace app\models;

use Yii;
use app\models\TestCalibr;
use app\models\SensorMonitors;

/**
 * This is the model class for table "test_logs".
 *
 * @property integer $id
 * @property string $terminal
 * @property integer $azs
 * @property integer $command
 * @property string $fuel
 * @property string $litrs
 * @property string $price
 * @property string $date
 * @property string $CardID
 * @property string $NameCompany
 * @property string $NameCustomer
 */
class TestLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['terminal', 'azs', 'command', 'fuel', 'litrs', 'price', 'date', 'CardID', 'NameCompany', 'NameCustomer'], 'required'],
            [['azs', 'command'], 'integer'],
            [['litrs', 'price'], 'number'],
            [['date'], 'safe'],
            [['terminal', 'CardID'], 'string', 'max' => 16],
            [['fuel'], 'string', 'max' => 10],
            [['NameCompany', 'NameCustomer'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'terminal' => 'Terminal',
            'azs' => 'Azs',
            'command' => 'Command',
            'fuel' => 'Fuel',
            'litrs' => 'Litrs',
            'price' => 'Price',
            'date' => 'Date',
            'CardID' => 'Card ID',
            'NameCompany' => 'Name Company',
            'NameCustomer' => 'Name Customer',
        ];
    }

    public function pull()
    {
        $date = strtotime($this->date) - 10600;

        $litr = $this->litrs;

        $h = $this->getFuelLevel();

        $density = $this->getDensity();

        $TestCalibr = new TestCalibr();
        $TestCalibr->date = $date;
        $TestCalibr->litr = $this->getCorrectLitr();
        $TestCalibr->h = $h;
        $TestCalibr->density = $density;

        if ($TestCalibr->validate())
            $TestCalibr->save();
    }

    public function getFuelLevel()
    {
        return $this->sensorMonitor["level"];
    }

    public function getDensity()
    {
        return $this->sensorMonitor["density"];
    }

    public function getSensorMonitor($time = false)
    {
        if ($time == false)
            $d1 = strtotime($this->date) - 10600;
        else
            $d1 = $time;

        $d2 = 0;
        $d2 = $d1 + 20;
        $monitors = SensorMonitors::find()->where([">=", "date", $d1])->andWhere(["<=", "date", $d2])->andWhere(["<>", "fuel_level", "0"])->all();
        $i = 0;
        $levels = 0;
        $densityes = 0;
        $level = 0;
        $density = 0;
    
        foreach ($monitors as $monitor)
        {
            $levels += $monitor->fuel_level;
            $densityes += $monitor->density;
            $i++; 
        }

        if ($i > 0)
        {
            $level = $levels/$i;
            $density = $densityes/$i;
        }

        $r["level"] = $level;
        $r["density"] = $density;

        return $r;
    }

    public function getCorrectLitr()
    {
        $lastRecord = TestCalibr::find()->where(["<=", "date", strtotime($this->date) - 10600])->orderBy(["date" => SORT_DESC])->one();
        if (!$lastRecord)
        {
            $date = strtotime($this->date) - 10600 - 900;

            $litr = "50000";

            $h = $this->getSensorMonitor($date)["level"];

            $density = $this->getSensorMonitor($date)["density"];

            $TestCalibr = new TestCalibr();
            $TestCalibr->date = $date;
            $TestCalibr->litr = $litr;
            $TestCalibr->h = $h;
            $TestCalibr->density = $density;
            $TestCalibr->save();

            $lastRecord = $TestCalibr;
        }

        $d2 = $this->getDensity();
        $l2 = $this->litrs;

        $d1 = $lastRecord->density;
        $l1 = $lastRecord->litr;
       

        return $l1 - (($d2 * $l2)/$d1);
    }
}
