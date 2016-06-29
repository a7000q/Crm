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
use app\models\SmsCenter;
use app\models\TerminalErrors;
use app\models\Terminals;
use app\models\TerminalCounter;
use app\models\Partners;
use app\models\CorrectFuelDelivery;
use yii\web\UploadedFile;
use app\models\Photos;
use app\models\RealTimeTranzactions;
use linslin\yii2\curl;

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
                        'actions' => ['sensor-monitor'],
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
        $SensorMonitors->setStatusSensor();

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

   public function actionTerminalFuelStep2($tranzaction, $id_section)
   {
        $tranzaction = Tranzactions::findOne($tranzaction);

        if (!$tranzaction)
          return json_encode(Tranzactions::setError(5));

        if ($tranzaction->status != 0 && $tranzaction->status != 2) 
          return json_encode(Tranzactions::setError(7));

        if ($tranzaction->status == 0)
            $result = $tranzaction->fill($id_section);
        else
            $result = $tranzaction->fillPerevod($id_section);

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

        $doza = str_replace(",", ".", $doza);

        if (!$tranzaction)
          return json_encode(Tranzactions::setError(5));

        if  ($tranzaction->status == 1)
            $result = $tranzaction->fuelBack($doza);
        else
            $result = $tranzaction->fuelBackPerevod($doza);

        if ($tranzaction->realTimeTranzactions)
        {
            $tranzaction->realTimeTranzactions->status = "success";
            $tranzaction->realTimeTranzactions->save();
        }

        return json_encode($result);
    }

    public function actionTerminalError($id_terminal, $text)
    {
        $TerminalErrors = new TerminalErrors();
        $TerminalErrors->date = time();
        $TerminalErrors->id_terminal = $id_terminal;
        $TerminalErrors->text = $text;

        $TerminalErrors->save();
        
        $result["status"] = "ok";
        return json_encode($result);
    }

    public function actionTerminalCounter($id_terminal, $id_tranzaction, $litr)
    {
        $litr = str_replace(",", ".", $litr);

        $TerminalCounter = new TerminalCounter();
        $TerminalCounter->date = time();
        $TerminalCounter->id_terminal = $id_terminal;
        $TerminalCounter->id_tranzaction = $id_tranzaction;
        $TerminalCounter->sumLitr = $litr;
        $TerminalCounter->save();

        $result["status"] = "ok";

        return json_encode($result);
    }

    public function init()
    {
    	
        if ($this->module->requestedRoute != 'api/ping-terminal' && $this->module->requestedRoute != 'api/sensor-monitor')
        {
            $request = new RequestServer();
        	$request->date = time();
        	$request->url = Yii::$app->request->url;
        	$request->ip = Yii::$app->request->userIP;
        	$request->validate();
        	$request->save();
        }
    }


   

    

    public function actionTestCalibr()
    {
        $time = new \DateTime("2016-03-16 17:04:00");
        $today = $time->format('Y-m-d H:i:s');
        $time2 = new \DateTime("2016-03-24 20:25:00");
        $today2 = $time2->format('Y-m-d H:i:s');
        $logs = TestLogs::find()->where(["command" => 2, "terminal" => "azsSanki25_3"])->andWhere([">=", "date", $today])
            ->andWhere(["<=", "date", $today2])->all();

        $sum = 0;
        $count = 0;
    

        foreach ($logs as $log) 
        {
            $log->pull();
            $sum += $log->litrs;
            $count++;
        }

        echo $count." ".$sum." ".date("d.m.Y H:i");
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

        return $this->render('graf', ['TestCalibr' => $TestCalibr, 'data' => $data]);
    }

    public function actionGetBadTranzaction()
    {
        $tranzactions = Tranzactions::find()->all();

        foreach ($tranzactions as $tranz) 
        {
            if (!$tranz->tranzactionHistory)
            {
                $tranz->delete();
            }
        }

        $cards = ['481', '483', '1', '532'];
        $tranz = Tranzactions::find()->where(['in', 'id_card', $cards])->all();

        foreach ($tranz as $t) 
        {
            $t->delete();
        }
    }

    public function actionDelTranzactionById($id)
    {
        $tranzaction = Tranzactions::findOne($id);
        $tranzaction->delete();
    }


    public function actionAddFill($id_terminal, $date, $id_card, $litr)
    {
        $tranzaction = new Tranzactions();

        $result = $tranzaction->addFill($id_terminal, $date, $id_card, $litr);
    }


    public function actionRealTimeTranzactionInfo($id_tranzaction, $doza)
    {
        $tranzaction = Tranzactions::findOne($id_tranzaction);

        $doza = str_replace(",", ".", $doza);

        if (!$tranzaction->realTimeTranzactions)
        {
            $realTime = new RealTimeTranzactions();
            $realTime->id_tranzaction = $tranzaction->id;
            $realTime->date = time();
            $realTime->doza = $doza*1;
            $realTime->status = "fuel";
            $realTime->validate();
            //print_r($realTime);
            $realTime->save();
        }
        else
        {
            $tranzaction->realTimeTranzactions->date = time();
            $tranzaction->realTimeTranzactions->doza = $doza*1;
            $tranzaction->realTimeTranzactions->validate();
            $tranzaction->realTimeTranzactions->save();
        }

        $r["status"] = 'ok';

        return json_encode($r);

    }

    public function actionPingTerminal($id)
    {
        $terminal = Terminals::findOne($id);
        $terminal->setVizit();

        $result["status"] = "ok";
        return json_encode($result);
    }

    public function actionPingStatus()
    {
        $terminals = Terminals::find()->all();
        foreach ($terminals as $terminal) 
            $terminal->runStatus();

        //print_r($terminals);
    }

    public function actionSendImage()
    {
        $post = Yii::$app->request->post();

        if (isset($post["id_tranzaction"]))
        {
            $id_tranzaction = $post["id_tranzaction"];

            $photo = new Photos();

            $photo->id_tranzaction = $id_tranzaction;

            $photo->file = UploadedFile::getInstanceByName('imageFile');

            //print_r($_FILES);

            if ($photo->upload()) {
                $result["status"] = 'ok';
            }
            else
                $result["status"] = 'error';

            return json_encode($result);

        }

        $result["status"] = "no-tranzaction";

        return json_encode($result);
    }

    public function beforeAction($action) {
        //echo $action->id;
        $this->enableCsrfValidation = ($action->id !== "send-image"); 
        return parent::beforeAction($action);
    }

    public function actionT()
    {

    }

    public function actionTest()
    {
        $correct = new CorrectFuelDelivery();
        $correct->correctDelivery();
    }


 
}
