<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\Model;
use app\models\SensorMonitors;
use app\models\Tranzactions;
use app\models\RequestServer;
use app\models\TestCard;

class ApiController extends CController
{
   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['sensor-monitor'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

   public function actionSensorMonitor($name, $fuel_level, $temp, $density, $water_level)
   {
        $SensorMonitors = new SensorMonitors();
        $SensorMonitors->date = time();
        $SensorMonitors->fuel_level = floatval($fuel_level);
        $SensorMonitors->temp = floatval($temp);
        $SensorMonitors->density = floatval($density);
        $SensorMonitors->water_level = floatval($water_level);
        $SensorMonitors->setSensorId($name);

        if ($SensorMonitors->validate())
        {
            $SensorMonitors->save();
            $SensorMonitors->sensor->fuelModuleSection->updatePriceDensityForBay();
        }
        else
            echo "Ошибка";
   }

   public function actionTerminalFuelStep1($id_terminal, $id_electro)
   {
        $tranzaction = new Tranzactions();

        $result = $tranzaction->createTranzaction($id_terminal, $id_electro);

        return json_encode($result);
   }

   public function actionTerminalFuelStep2($tranzaction, $id_section, $doza)
   {
        $tranzaction = Tranzactions::findOne($tranzaction);

        if (!$tranzaction)
          return json_encode(Tranzactions::setError(5));

        if ($tranzaction->status != 0)
          return json_encode(Tranzactions::setError(7));

        $result = $tranzaction->fill($id_section, $doza);

        return json_encode($result);
   }

    public function actionTerminalFuelBackStep1($id_terminal, $id_electro)
    {
        $result = Tranzactions::findLastTranzaction($id_terminal, $id_electro);

        return json_encode($result);
    }

    public function actionTerminalFuelBackStep2($tranzaction, $doza)
    {
        $tranzaction = Tranzactions::findOne($tranzaction);

        if (!$tranzaction)
          return json_encode(Tranzactions::setError(5));

        $result = $tranzaction->fuelBack($doza);

        return json_encode($result);
    }

    public function init()
    {
    	$request = new RequestServer();
    	$request->date = time();
    	$request->url = Yii::$app->request->url;
    	$request->ip = Yii::$app->request->userIP;
    	$request->validate();
    	$request->save();
    }

    public function actionPing($id_terminal)
    {
        $result["status"] = "ok";

        return json_encode(value);
    }

    public function actionTest()
    {
        $cards = TestCard::find()->all();

        foreach ($cards as $card) 
        {
           $card->pull();
        }
    }





 
}
