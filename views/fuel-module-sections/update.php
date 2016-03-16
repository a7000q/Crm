<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FuelModuleSections */

$this->title = 'Изменение секции топливного модуля: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Топливные модули', 'url' => ['fuel-module/index']];
$this->params['breadcrumbs'][] = ['label' => 'Секции топливного модуля', 'url' => ['index', 'id' => $model->id_module]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="fuel-module-sections-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
