<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\assets\UIConfirmationsAsset;
UIConfirmationsAsset::register($this); 
/* @var $this yii\web\View */
/* @var $model app\models\BankAccounts */

$this->title = $model->checking_account;
$this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = ['label' => 'Расчетные счета '.$model->partner->name, 'url' => ['index', 'id' => $model->id_partner]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-accounts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(['action' => ['delete', 'id' => $model->id],  'enableClientValidation' => false]); ?>
            <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <button class="btn btn-danger" data-toggle="confirmation" data-original-title="Вы уверены что хотите удалить данный элемент?" title="">Удалить</button>
        <?php ActiveForm::end(); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bank_name',
            'checking_account',
            'corresponding_account',
            'bik',
        ],
    ]) ?>

</div>
