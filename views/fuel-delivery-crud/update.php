<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FuelDelivery */

$this->title = 'Update Fuel Delivery: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fuel Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fuel-delivery-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
