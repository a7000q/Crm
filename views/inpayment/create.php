<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Inpayment */

$this->title = 'Добавление оплаты';
$this->params['breadcrumbs'][] = ['label' => 'Оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inpayment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
