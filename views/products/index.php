<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Продукты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

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
                    return Html::a($data->name, Url::toRoute(['product-passports/index', 'id' => $data->id]));
                }
            ],
            'short_name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
