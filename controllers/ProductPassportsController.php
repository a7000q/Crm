<?php

namespace app\controllers;

use Yii;
use app\models\ProductPassports;
use app\models\Products;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;

/**
 * ProductPassportsController implements the CRUD actions for ProductPassports model.
 */
class ProductPassportsController extends CController
{

    /**
     * Lists all ProductPassports models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProductPassports::find()->where(['id_product' => $id]),
        ]);

        $product = Products::findOne($id);

        return $this->render('index', [
            'dataProvider' => $dataProvider, 'id_product' => $id, 'product' => $product
        ]);
    }

    /**
     * Displays a single ProductPassports model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProductPassports model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ProductPassports();

        if (Yii::$app->request->isPost) 
        {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->upload($id)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model, 'id_product' => $id
            ]);
        }
    }

    /**
     * Updates an existing ProductPassports model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProductPassports model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_product = $model->id_product;
        $model->delete();

        return $this->redirect(['index', 'id' => $id_product]);
    }

    /**
     * Finds the ProductPassports model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductPassports the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductPassports::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
