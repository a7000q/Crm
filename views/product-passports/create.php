<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductPassports */

$this->title = 'Создание паспорта продукта';
$this->params['breadcrumbs'][] = ['label' => 'Продукты', 'url' => ['products/index']];
$this->params['breadcrumbs'][] = ['label' => 'Паспорт продукта', 'url' => ['index', 'id' => $id_product]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-passports-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
