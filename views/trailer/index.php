<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Прицепы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trailers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'gos_number',
            [
                    'label' => 'Секции',
                    'format' => 'raw',
                    'value' => function($data){
                        return Html::a('Открыть', Url::toRoute(['sections/index', 'id' => $data->id]));
                    }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
