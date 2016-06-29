<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fuel Deliveries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fuel-delivery-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'dateText',
            'fuelModule.name',
            // 'driver',
            // 'id_product_passport',
            // 'id_trailer',
            // 'gos_number',
            'kalibr',
            'volume',
            'fakt_volume',
            // 'mass',
            // 'fakt_mass',
            // 'diff_mass',
            // 'price',
            // 'price_track',
            // 'id_partner',
            // 'id_partner_track',
            'priceLitr',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
