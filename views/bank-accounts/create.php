<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BankAccounts */

$this->title = 'Создание расчетного счета';
$this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = ['label' => 'Расчетные счета '.$model->partner->name, 'url' => ['index', 'id' => $model->id_partner]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-accounts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
