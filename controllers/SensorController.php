<?php

namespace app\controllers;
use app\models\Sensors;
use yii\data\ActiveDataProvider;

class SensorController extends CController
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Sensors::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
