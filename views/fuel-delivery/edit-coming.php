<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use app\assets\Select2Asset;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
Select2Asset::register($this); 

$this->registerJs("$('#fuel_module').select2({
                  placeholder: 'Выберите топливный модуль'
                });", yii\web\View::POS_READY);

$this->registerJs("$('#products').select2({
                  placeholder: 'Выберите продукт'
                });", yii\web\View::POS_READY);

$this->registerJs("$('#trailers').select2({
                  placeholder: 'Выберите прицеп'
                });", yii\web\View::POS_READY);

$this->registerJs("$('.del_sect').click(function(){
                      obj = $(this).parent('td').parent('tr').parent('tbody').parent('table').parent('div');
                      id_section = $(obj).attr('data-id');
                      $('#section_block').append('<input type=\'hidden\' name=\'UpdateFuelDeliveryForm[remove_section][]\' value=\''+id_section+'\'>');
                      $(obj).remove();
                    });", yii\web\View::POS_READY);

$this->title = "Изменение прихода товара №".$FuelDelivery->FuelDelivery->id;
$this->params['breadcrumbs'][] = ['label' => 'Приход товара', 'url' => ['comings']];
$this->params['breadcrumbs'][] = ['label' => 'Приход товара №'.$FuelDelivery->FuelDelivery->id, 'url' => ['coming', 'id' => $FuelDelivery->FuelDelivery->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="fuel-delivery-coming">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => '<tr><td>{label}</td><td>{input}{error}</td></tr>']]); ?>
        <table class="table table-bordered table-hover">
            <?=$form->field($FuelDelivery, 'id_fuel_module')->dropDownList($FuelDelivery->fuelModules, ['id' => 'fuel_module'])?>
            <?=$form->field($FuelDelivery, 'id_fuel_module_section')->dropDownList($FuelDelivery->fuelModuleSections, ['id' => 'fuel_module_section'])?>
            <?=$form->field($FuelDelivery, 'driver')->widget(Typeahead::classname(), ['dataset' => [['local' => $FuelDelivery->drivers, 'limit' => 10]],'pluginOptions' => ['highlight' => true],'options' => ['placeholder' => 'Укажите Фамилию и инициалы водителя']]);?>
            <?=$form->field($FuelDelivery, 'id_product')->dropDownList($FuelDelivery->products, ['id' => 'products'])?>
            <?=$form->field($FuelDelivery, 'id_trailer')->dropDownList($FuelDelivery->trailers, ['id' => 'trailers'])?>
        </table>


        <?foreach ($FuelDelivery->activeSections as $section):?>
            <div class="col-md-4" data-id="<?=$section->id?>">
                <table class="table table-bordered table-hover">
                     <tr>
                        <td><b>№</b></td>
                        <td><?=$section->section->name?></td>
                     </tr>
                     <?=$form->field($FuelDelivery, "volume[$section->id]")?>
                     <?=$form->field($FuelDelivery, "density[$section->id]")?>
                     <?=$form->field($FuelDelivery, "temp[$section->id]")?>
                     <?=$form->field($FuelDelivery, "mass[$section->id]")?>
                     <tr>
                        <td colspan="2">
                            <a href="#" class='btn btn-danger btn-block del_sect'>Удалить</a>
                        </td>
                     </tr>
                </table>
            </div>
        <?endforeach;?>
        <div id="section_block">
        </div>
        <table class="table table-bordered table-hover">
            <?= Html::submitButton('Продолжить', ['class' => 'btn btn-success', 'name' => 'nextButton', 'style' => 'width: 100%; margin-bottom: 15px;']) ?>
            <?= Html::submitButton('Восстоновить все секции', ['class' => 'btn btn-primary', 'name' => 'resetSection', 'style' => 'width: 100%; margin-bottom: 15px;']) ?>
            <?= Html::submitButton('Вернуться назад', ['class' => 'btn btn-danger', 'name' => 'resetSection', 'style' => 'width: 100%']) ?>
        </table>
    <?php ActiveForm::end(); ?>

</div>