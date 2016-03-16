<?php

namespace app\controllers;

use Yii;
use app\models\AddFuelDeliveryForm;
use app\models\UpdateFuelDeliveryForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\FuelDelivery;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;


class FuelDeliveryController extends CController
{
    

    public function actionIndex()
    {
        $post = Yii::$app->request->post();
        $model = new AddFuelDeliveryForm();
        $success = false;
        $session = Yii::$app->session;

        if (isset($post['AddFuelDeliveryForm']))
            $model->load($post);

        if (isset($post["prevButton"]))
        {
            $model = $session["model"];
            unset($session["model"]);
        }

        if (isset($post["saveData"]))
        {
            $model = $session["model"];
            if ($model->createDelivery())
            {
                $model = new AddFuelDeliveryForm();
                unset($session["model"]);
                $success = true;
            }
        }

        if (isset($post["resetRemoveSection"]))
            $model->remove_section = array();

        if (isset($post["nextButton"]) and $model->validate())
        {
            $model->formData();
            $session["model"] = $model;
            return $this->render('step2', ['model' => $model]);
        }

        return $this->render('index', ['model' => $model, 'success' => $success]);
    }

    public function actionComings()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FuelDelivery::find()->where(['id_partner' => 0])->orderBy(["date" => SORT_DESC]),
        ]);

        return $this->render('comings', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionComing($id)
    {
        $FuelDelivery = $this->findModel($id);
        $post = Yii::$app->request->post();

        if (isset($post['saveButton']))
        {
            $FuelDelivery->scenario = 'step2';
            $FuelDelivery->load($post);

            if ($FuelDelivery->validate())
            {
                $FuelDelivery->correctPrice();
                $FuelDelivery->save();
                $FuelDelivery->addFuelBalance();
                $this->redirect(['comings']);
            }
        }

        return $this->render('coming', ['FuelDelivery' => $FuelDelivery]);
    }

    public function actionEditComing($id)
    {
        $post = Yii::$app->request->post();
        $session = Yii::$app->session;

        $FuelDelivery = new UpdateFuelDeliveryForm();

        if (!isset($session["model"]))
            $FuelDelivery->loadDelivery($id);
        else
            $FuelDelivery = $session["model"];


        if (isset($post['UpdateFuelDeliveryForm']))
            $FuelDelivery->load($post);

        if (isset($post["nextButton"]))
        {
            $FuelDelivery->formData();
            $session["model"] = $FuelDelivery;
            return $this->render('step2edit-coming', ['model' => $FuelDelivery]);
        }

        if (isset($post["prevButton"]))
        {
            $FuelDelivery = $session["model"];
            unset($session["model"]);
        }

        return $this->render('edit-coming', ['FuelDelivery' => $FuelDelivery]);

    }

    protected function findModel($id)
    {
        if (($model = FuelDelivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
