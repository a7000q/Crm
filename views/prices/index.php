<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Прайс '.$partner->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prices-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create', "id" => $partner->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'product.short_name',
            'type.name',
            'price',
            [
                'label' => "Доп. соглашение",
                'format' => 'raw',
                'value' => function($data){
                    if ($data->file_src != "")
                        return Html::a('Открыть', $data->file_src, ["target" => "_blank"]);
                    else
                        return "";
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
