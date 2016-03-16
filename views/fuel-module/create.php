<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FuelModule */

$this->title = 'Создание топливного модуля';
$this->params['breadcrumbs'][] = ['label' => 'Топливные модули', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fuel-module-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
