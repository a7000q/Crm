<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Trailers */

$this->title = 'Изменение прицепа: ' . ' ' . $model->gos_number;
$this->params['breadcrumbs'][] = ['label' => 'Прицепы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gos_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение прицепа';
?>
<div class="trailers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
