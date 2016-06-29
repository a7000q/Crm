<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inpayment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'dateText',
            'partner.name',
            'sum',
            [
                'attribute' => 'scan_plateg_src',
                'value' => function($data)
                {
                    if ($data->scan_plateg_src != "")
                        return Html::a('Открыть', $data->scan_plateg_src);
                    else
                        return "";
                },
                'format' => 'html'
            ]
            ,
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}'
            ],
        ],
    ]); ?>
</div>
