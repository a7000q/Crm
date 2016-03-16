<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Приход товара';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('.fuelLine').click(function(){
                    href = '".Url::toRoute(['fuel-delivery/coming'])."';
                    id = $(this).attr('data-key');
                    location.href = href + '&id=' + id;
                });", yii\web\View::POS_READY);
?>
<div class="bank-accounts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'dateText',
            'fuelModule.name',
            'fuelModuleSection.name',
            'gos_number',
            'fakt_volume',
            'fakt_mass',
            [
                'label' => "Разница",
                'format' => 'raw',
                'value' => function($data){
                    return $data->diff_mass;
                }
            ],
        ],
        'rowOptions' => ['class' => "fuelLine"]
    ]); ?>

</div>