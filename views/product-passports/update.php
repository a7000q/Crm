<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductPassports */

$this->title = 'Изменение паспорта продукта: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['products/index']];
$this->params['breadcrumbs'][] = ['label' => 'Паспорт продукта', 'url' => ['index', 'id' => $model->id_product]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="product-passports-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
