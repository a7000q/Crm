<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['partners/index']];
$this->title = 'Расчетные счета '.$partner->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-accounts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create', 'id' => $partner->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'bik',
            'bank_name',
            'checking_account',
            'corresponding_account',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
