<?php

namespace app\controllers;

use Yii;
use app\models\reports\SaleReport;
use app\models\reports\BayReport;
use app\models\reports\ErrorReport;
use app\models\reports\TransferReport;
use app\models\FuelModule;
use app\models\reports\ChartBalanceReport;

class ReportsController extends CController
{
    public function actionSale()
    {
        $model = new SaleReport();
        $session = Yii::$app->session;

        $post = Yii::$app->request->post();
        if (isset($post["SaleReport"]))
        {
            $model->load($post);
            $session["fmodel"] = $model;
        }else if (isset($session["fmodel"]))
            $model = $session["fmodel"];

        return $this->render('sale', ['model' => $model]);
    }

    public function actionTransfer()
    {
    	$model = new TransferReport();

    	$post = Yii::$app->request->post();
        if (isset($post["TransferReport"]))
        {
            $model->load($post);
        }

        return $this->render('transfer', ['model' => $model]);
    }

    public function actionSaleDetail($id)
    {
        $model = new SaleReport();

        $data = $model->getDetail($id);

        if ($data == "")
        {
            $this->redirect(['reports/sale']);
            return false;
        }

        return $this->render('sale-detail', ['data' => $data]);
    }

    public function actionBay()
    {
    	$model = new BayReport();

    	return $this->render("bay", ['model' => $model]);
    }

    public function actionErrorTerminals()
    {
        $model = new ErrorReport();

        $post = Yii::$app->request->post();
        if (isset($post["ErrorReport"]))
        {
            $model->load($post);
        }

        return $this->render('error', ['model' => $model]);
    }

    public function actionFuelModule()
    {
    	$FuelModule = FuelModule::find()->all();

    	$post = Yii::$app->request->post();

    	$date = time();

    	if (isset($post["dateSearch"]))
    	{
    		$date = strtotime($post["dateSearch"]);
    	}

    	return $this->render('fuelModule', ['modules' => $FuelModule, 'date' => $date]);
    }

    public function actionChartBalance()
    {
        $model = new ChartBalanceReport();

        $post = Yii::$app->request->post();

        if (isset($post["ChartBalanceReport"]))
        {
            $model->load($post);
            $model->formData();
        }

        return $this->render('chartBalance', ['model' => $model]);
    }
}
