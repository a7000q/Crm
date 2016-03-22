<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prices */

$this->title = 'Изменение прайса: ' . $model->product->short_name." для ".$model->partner->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = ['label' => 'Прайс '.$model->partner->name, 'url' => ['index', "id" => $model->id_partner]];
$this->params['breadcrumbs'][] = ['label' => $model->product->short_name." для ".$model->partner->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="prices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
