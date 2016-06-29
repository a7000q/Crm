<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\assets\Select2Asset;
Select2Asset::register($this); 
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проведение перемещения №'.$model->id;
$this->params['breadcrumbs'][] = $this->title;


$this->registerJs("$('#fuel_module_select').select2({
                  placeholder: 'Выберите топливный модуль'
                });", yii\web\View::POS_READY);

$this->registerJs("$('#fuel_module_section_select').select2({
                  placeholder: 'Выберите секцию'
                });", yii\web\View::POS_READY);

$this->registerJs("$('#fuel_module_select, #fuel_module_section_select').change(function(){
                          $('#transferForm').submit();
                        });", yii\web\View::POS_READY);

?>
<div class="trailers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'transferForm', 'fieldConfig' => ['template' => '{input}']]); ?>
        <table class="table table-bordered table-hover" style="width: 100%;">
            <tr>
                <td><b>Дата</b></td>
                <td><?=$model->dateText?></td>
            </tr>
            <tr>
                <td><b>Карта, №</b></td>
                <td><?=$model->card->id_txt?></td>
            </tr>
            <tr>
                <td><b>Имя</b></td>
                <td><?=$model->card->name?></td>
            </tr>
            <tr>
                <td><b>Литры, л</b></td>
                <td><?=$model->doza?></td>
            </tr>
            <tr>
                <td><b>Топливный модуль</b></td>
                <td><?=$model->section->module->name?></td>
            </tr>
        </table>

        <?=$form->field($transfer, 'id_module')->dropDownList($transfer->modules, ['prompt' => 'Выберите топливный модуль', 'class' => "form-control select2", 'id' => 'fuel_module_select'])?>

        <?if ($transfer->id_module):?>
            <?if (count($transfer->sections) > 1):?>
                <?=$form->field($transfer, 'id_section')->dropDownList($transfer->sections, ['prompt' => 'Выберите секцию', 'class' => 'form-control select2', 'id' => 'fuel_module_section_select'])?>
            <?endif;?>

            <button type="submit" class="btn btn-success" name="saveTransfer">Сохранить</button>
        <?endif;?>

        <?//= $form->field($transfer, 'id_section')->dropDownList($transfer->sections, ['id' => 'filterCompany', 'class' => "form-control select2-multiple", "multiple" => true, 'placeholder' => 'Выберите компанию'])?>

    <?php ActiveForm::end(); ?>

</div>
