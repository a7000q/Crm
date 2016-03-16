<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccounts */

$this->title = 'Изменение рассчетного счета: ' . ' ' . $model->checking_account;
$this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = ['label' => 'Расчетные счета '.$model->partner->name, 'url' => ['index', 'id' => $model->id_partner]];
$this->params['breadcrumbs'][] = ['label' => $model->checking_account, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="bank-accounts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
