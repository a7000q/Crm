<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

$this->registerJs("$('#d1, #d2').datetimepicker({
            language: 'ru',
            format: 'dd.mm.yyyy hh:ii'
        });");

?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(); ?>

        <table class="table" style="width: 100%;">
            <tr>
                <td><?= $form->field($model, 'd1Text')->textInput(['id' => 'd1']) ?></td>
                <td><?= $form->field($model, 'd2Text')->textInput(['id' => 'd2']) ?></td>
            </tr>
        </table>

        <?= $form->field($model, 'id_account')->dropDownList($model->accounts) ?>

        <?= $form->field($model, 'id_partner')->dropDownList($model->partners) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
