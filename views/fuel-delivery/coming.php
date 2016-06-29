<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use app\assets\Select2Asset;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
Select2Asset::register($this); 

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

$this->registerJs("$('.blankWindow').attr('target','_blank');", yii\web\View::POS_READY);

$this->registerJs("$('#partners').select2({
                      placeholder: 'Выберите поставщика'
                    });", yii\web\View::POS_READY);

$this->registerJs("$('#partners_track').select2({
                      placeholder: 'Выберите поставщика'
                    });", yii\web\View::POS_READY);

$this->registerCss(".radio-list > .radio{ width:40%; }");

$this->registerCss(".select2{ width:40%; }");


$this->registerJs("$('#date').datetimepicker({
                    language: 'ru',
                    format: 'dd.mm.yyyy hh:ii',
                    autoclose: true,
                    isRTL: App.isRTL()
                });", yii\web\View::POS_READY);

$this->title = "Приход товара №".$FuelDelivery->id;
$this->params['breadcrumbs'][] = ['label' => 'Приход товара', 'url' => ['comings']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="fuel-delivery-coming">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $FuelDelivery,
        'attributes' => [
            'dateText',
            'fuelModule.name',
            'fuelModuleSection.name',
            'driver',
            'user.full_name',
            'product.name',
            [
                'label' => 'Паспорт продукта',
                'value' => ($FuelDelivery->productPassport)?Html::a($FuelDelivery->productPassport->name, $FuelDelivery->productPassport->src, ['class' => 'blankWindow']):"(не задано)",
                'format' => 'raw'
            ],
            'gos_number',
            'kalibr',
            'volume',
            'fakt_volume',
            'mass',
            'fakt_mass',
            'diff_mass'
        ],
    ]) ?>

    <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => '{input}']]); ?>
        <table class="table table-bordered table-hover">
            <tr>
                <td><b>Укажите дату</b></td>
                <td>
                    <?= $form->field($FuelDelivery, 'dateText')->textInput(['placeholder' => 'Укажите дату', 'id' => 'date']) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $form->field($FuelDelivery, 'price')->textInput(['value' => '', 'placeholder' => 'Цена за тонну']) ?>
                </td>
                <td>
                    <?= $form->field($FuelDelivery, 'id_partner')->dropDownList($FuelDelivery->partners, ['class' => "form-control select2", 'id' => 'partners']) ?>
                </td>
            </tr>
            <tr>
                <td><?= $form->field($FuelDelivery, 'price_track')->textInput(['value' => '', 'placeholder' => 'Цена ТЗР']) ?></td>
                <td><?= $form->field($FuelDelivery, 'id_partner_track')->dropDownList($FuelDelivery->partners, ['class' => "form-control select2", 'id' => 'partners_track']) ?></td>
            </tr>
        </table>
        <?= $form->field($FuelDelivery, 'typePrice')->radioList(['1' => 'Цена за тонну', '2' => 'Цена за рейс'], ['class' => 'radio-list']) ?>
        
        <?= Html::a('Отмена', ['comings'], ['class' => 'btn btn-danger', 'name' => 'prevButton']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'saveButton']) ?>
    <?php ActiveForm::end(); ?>

</div>