<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FuelModuleSections */

$this->title = 'Создание секции топливного модуля';
$this->params['breadcrumbs'][] = ['label' => 'Топливные модули', 'url' => ['fuel-module/index']];
$this->params['breadcrumbs'][] = ['label' => 'Секции топливного модуля', 'url' => ['index', 'id' => $model->id_module]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fuel-module-sections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
