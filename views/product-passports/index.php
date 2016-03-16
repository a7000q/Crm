<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['products/index']];
$this->title = 'Паспорт продукта';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-passports-index">

    <h1><?=$product->name;?> <?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create', 'id' => $id_product], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'label' => 'Файл',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->src, $data->src);
                }
            ],
            'dateText',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
