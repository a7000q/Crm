<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

$this->registerJs("$('#date').datepicker({
            language: 'ru',
            format: 'dd.mm.yyyy',
            minView: 2
        });
");

/* @var $this yii\web\View */
/* @var $model app\models\Inpayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inpayment-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'dateText')->textInput(['class' => 'form-control form-control-inline input-medium date-picker', 'id' => 'date']) ?>

    <?= $form->field($model, 'id_partner')->dropDownList($model->partners) ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
