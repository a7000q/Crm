<?php

namespace app\controllers;

use Yii;
use app\models\AddFuelDeliveryForm;

class FuelDeliveryController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	
    	$post = Yii::$app->request->post();
    	$model = new AddFuelDeliveryForm();
    	$success = false;

    	if (isset($post['AddFuelDeliveryForm']))
    		$model->load($post);

    	//print_r($model);

    	if (isset($post["nextButton"]) and $model->validate() and $model->createDelivery())
    	{
    		$model = new AddFuelDeliveryForm();
    		$success = true;
    	}

        return $this->render('index', ['model' => $model, 'success' => $success]);
    }

}
