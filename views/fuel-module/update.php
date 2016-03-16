<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FuelModule */

$this->title = 'Изменение топливного модуля: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Топливные модули', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="fuel-module-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
