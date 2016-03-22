<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
	use yii\widgets\Pjax;
	use yii\widgets\MaskedInput;
	use app\assets\Select2Asset;
	use kartik\typeahead\TypeaheadBasic;
	use kartik\typeahead\Typeahead;
	Select2Asset::register($this); 

	use app\assets\InputMaskAsset;
	InputMaskAsset::register($this);

	/* @var $this yii\web\View */
	$this->title = 'Продажа топлива';

	$this->registerJs("$('#select_inp, #fuel_module_select, #fuel_module_section_select').change(function(){
						  $('#fuelDelivery').submit();
						});", yii\web\View::POS_READY);

	$this->registerJs("$('#fuel_module_select').select2({
					  placeholder: 'Выберите топливный модуль'
					});", yii\web\View::POS_READY);

	$this->registerJs("$('#fuel_module_section_select').select2({
					  placeholder: 'Выберите секцию топливного модуля'
					});", yii\web\View::POS_READY);

	$this->registerJs("$('#partner').select2({
					  placeholder: 'Выберите контрагента'
					});", yii\web\View::POS_READY);
?>

<div class="fuel-delivery">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'f_pjax']); ?>
		<?php $form = ActiveForm::begin(['id' => 'fuelDelivery', 'fieldConfig' => ['template' => '{input}']]); ?>

			<?= $form->field($model, 'id_fuel_module')->dropDownList($model->fuelModules, ['prompt' => 'Выберите топливный модуль', 'class' => "form-control select2", 'id' => 'fuel_module_select']) ?>

			<?if ($model->fuelModuleSections):?>
				
		    	<?= $form->field($model, 'id_fuel_module_section')->dropDownList($model->fuelModuleSections, ['prompt' => 'Выберите секцию топливного модуля', 'class' => "form-control select2", 'id' => 'fuel_module_section_select']) ?>

		    	<?if ($model->id_fuel_module_section):?>

			    	<?= $form->field($model, 'id_partner')->dropDownList($model->partners, ['prompt' => 'Выберите контрагента', 'class' => "form-control select2", 'id' => 'partner']) ?>

			    	<?= $form->field($model, 'count_litrs')->textInput(['placeholder' => "Количество литров"])?>

			    	<div class="form-group">
				        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block', 'name' => 'saveButton']) ?>
				    </div>

			    <?endif;?>

			<?endif;?>

	    <?php ActiveForm::end(); ?>
	<?php Pjax::end(); ?>

</div>

