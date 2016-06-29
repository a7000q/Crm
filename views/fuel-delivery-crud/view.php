<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FuelDelivery */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fuel Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fuel-delivery-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_user',
            'date',
            'id_fuel_module',
            'id_fuel_module_section',
            'driver',
            'id_product_passport',
            'id_trailer',
            'gos_number',
            'kalibr',
            'volume',
            'fakt_volume',
            'mass',
            'fakt_mass',
            'diff_mass',
            'price',
            'price_track',
            'id_partner',
            'id_partner_track',
            'priceLitr',
        ],
    ]) ?>

</div>
