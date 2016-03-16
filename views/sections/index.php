<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Секции';
$this->params['breadcrumbs'][] = ['label' => 'Прицепы', 'url' => ['trailer/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sections-index">

    <h1><?=$trailer->gos_number;?> <?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create', 'id' => $id_trailer], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'volume',
            'volume_pipe',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
