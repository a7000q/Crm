<?php

namespace app\controllers;

use Yii;
use app\models\reports\SaleReport;
use app\models\reports\BayReport;

class ReportsController extends CController
{
    public function actionSale()
    {
        $model = new SaleReport();

        $post = Yii::$app->request->post();
        if (isset($post["SaleReport"]))
        {
            $model->load($post);
        }

        return $this->render('sale', ['model' => $model]);
    }

    public function actionBay()
    {
    	$model = new BayReport();

    	return $this->render("bay", ['model' => $model]);
    }
}
