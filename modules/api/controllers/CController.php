<?php

namespace app\modules\api\controllers;
use yii\rest\ActiveController;
use yii\web\Response;

class CController extends ActiveController
{
    public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
	    return $behaviors;
	}
}
