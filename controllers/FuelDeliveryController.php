<?php

namespace app\controllers;

use Yii;
use app\models\AddFuelDeliveryForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class FuelDeliveryController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        
        $post = Yii::$app->request->post();
        $model = new AddFuelDeliveryForm();
        $success = false;

        if (isset($post['AddFuelDeliveryForm']))
            $model->load($post);

        if (isset($post["resetRemoveSection"]))
            $model->remove_section = array();

        if (isset($post["nextButton"]) and $model->validate() and $model->createDelivery())
        {
            $model = new AddFuelDeliveryForm();
            $success = true;
        }

        return $this->render('index', ['model' => $model, 'success' => $success]);
    }

}
