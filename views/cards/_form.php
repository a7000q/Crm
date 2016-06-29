<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FuelModuleSections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fuel-module-sections-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_txt')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'id_type_limit')->dropDownList($model->typeLimitsArray)?>

  	<?= $form->field($model, 'id_type_measurement_limit')->dropDownList($model->typeMeasurementsArray)?>
  
    <?= $form->field($model, 'value_limit')->textInput()?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
