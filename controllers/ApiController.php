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
use app\models\TestLogs;
use app\models\TestCalibr;

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

    public function actionTerminalFuelBackStep2($tranzaction, $doza, $status = false)
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

    public function actionTestCalibr()
    {
        $time = new \DateTime("2016-03-16 17:04:00");
        $today = $time->format('Y-m-d H:i:s');
        $logs = TestLogs::find()->where(["command" => 2, "terminal" => "azsSanki25_3"])->andWhere([">=", "date", $today])->all();
    

        foreach ($logs as $log) 
        {
            $log->pull();
        }
    }

    public function actionTestGraf()
    {
        $TestCalibr = TestCalibr::find()->orderBy(["date" => SORT_ASC])->all();

        $get = Yii::$app->request->get();
        $data = "";

        if (isset($get["h"]))
        {
            $h = $get["h"];
            $minH = TestCalibr::minH($h);
            $maxH = TestCalibr::maxH($h);
            $data["minH"] = "none";
            $data["maxH"] = "none";

            if ($minH)
                $data["minH"] = $minH;

            if ($maxH)
                $data["maxH"] = $maxH;

            $litr = (($maxH->coordsLitr - $minH->coordsLitr)/($maxH->h - $minH->h))*($h - $minH->h)+$minH->coordsLitr;
            $data["litr"] = $litr;

            //print_r($maxH);

            $data["h"] = $h;
        }

        return $this->renderPartial('graf', ['TestCalibr' => $TestCalibr, 'data' => $data]);
    }





 
}
