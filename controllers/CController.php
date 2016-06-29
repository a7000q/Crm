<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class CController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            //print_r($action);
            //die();
            if (!Yii::$app->user->isGuest)
            {
                if ($action->controller->id == 'c' and $action->id == 'index')
                    return true;

                if (!Yii::$app->user->identity->isPermissionAction(['c' => $action->controller->id, 'a' => $action->id]))
                    return false;
            }

            return true; // or false if needed
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        $this->redirect(Yii::$app->user->identity->homeUrl());
    }

   
}
