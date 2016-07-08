<?php

namespace app\modules\api\controllers;
use yii\rest\ActiveController;
use yii\web\Response;
use app\modules\api\models\Sensors;

class SensorController extends CController
{
    public $modelClass = 'app\modules\api\models\Sensors';

    public function actionSetData($id_terminal, $h, $density, $temp, $water_level)
    {
    	$Sensors = Sensors::findByTerminal($id_terminal);

    	if ($Sensors)
    		return $Sensors->fixValue($h, $density, $temp, $water_level);
    }
}
