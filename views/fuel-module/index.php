<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Топливные модули';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fuel-module-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Название',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->name, Url::toRoute(['fuel-module-sections/index', 'id' => $data->id]));
                }
            ],
            'address',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
