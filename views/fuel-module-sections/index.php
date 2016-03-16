<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Секции топливного модуля '.$FuelModule->name;
$this->params['breadcrumbs'][] = ['label' => 'Топливные модули', 'url' => ['fuel-module/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fuel-module-sections-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create', 'id' => $id_module], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'volume',
            'product.name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
