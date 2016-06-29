<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FuelModuleSections */

$this->title = 'Изменение карты: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = ['label' => 'Карты', 'url' => ['index', 'id' => $model->id_partner]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="fuel-module-sections-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
