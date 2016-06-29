<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\assets\UIConfirmationsAsset;
UIConfirmationsAsset::register($this); 
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Inpayment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inpayments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inpayment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(['action' => ['delete', 'id' => $model->id],  'enableClientValidation' => false]); ?>
            <button class="btn btn-danger" data-toggle="confirmation" data-original-title="Вы уверены что хотите удалить данный элемент?" title="">Удалить</button>
        <?php ActiveForm::end(); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date',
            'id_partner',
            'sum',
            'scan_plateg_src',
        ],
    ]) ?>

</div>
