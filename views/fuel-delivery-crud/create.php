<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FuelDelivery */

$this->title = 'Create Fuel Delivery';
$this->params['breadcrumbs'][] = ['label' => 'Fuel Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fuel-delivery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
