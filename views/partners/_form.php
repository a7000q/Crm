<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Partners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partners-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fakt_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pravo_forma')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'okved')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'okato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'oktmo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'okogu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'okfs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'okopf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'okpo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phoneSms')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'director')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'osnovanie')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'limit')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
