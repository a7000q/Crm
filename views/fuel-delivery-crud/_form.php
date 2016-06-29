<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FuelDelivery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fuel-delivery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dateText')->textInput() ?>

    <?= $form->field($model, 'kalibr')->textInput() ?>

    <?= $form->field($model, 'volume')->textInput() ?>

    <?= $form->field($model, 'mass')->textInput() ?>

    <?= $form->field($model, 'id_partner')->dropDownList($model->partners) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'id_partner_track')->dropDownList($model->partners) ?>

    <?= $form->field($model, 'price_track')->textInput() ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
