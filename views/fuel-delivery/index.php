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
	$this->title = 'Поступление топлива';

	$this->registerJs("$('#select_inp').select2({
						  placeholder: 'Выберите прицеп'
						});", yii\web\View::POS_READY);

	$this->registerJs("$('#fuel_module_select').select2({
						  placeholder: 'Выберите топливный модуль'
						});", yii\web\View::POS_READY);

	$this->registerJs("$('#fuel_module_section_select').select2({
						  placeholder: 'Выберите секцию топливного модуля'
						});", yii\web\View::POS_READY);

	$this->registerJs("$('#product_select').select2({
						  placeholder: 'Выберите продукт'
						});", yii\web\View::POS_READY);

	$this->registerJs("$('#select_inp, #fuel_module_select, #fuel_module_section_select').change(function(){
						  $('#fuelDelivery').submit();
						});", yii\web\View::POS_READY);

	$this->registerJs("$('.sect_class').click(function(){
						  obj = $(this).parent('td').parent('tr').parent('tbody').parent('table').parent('div');
						  id_section = $(obj).attr('data-id');
						  $('#section_block').append('<input type=\'hidden\' name=\'AddFuelDeliveryForm[remove_section][]\' value=\''+id_section+'\'>');
						  $(this).parent('td').parent('tr').parent('tbody').parent('table').parent('div').remove();
						});", yii\web\View::POS_READY);

	if ($success)
		$this->registerJs("alert('Прием топлива завершен!');", yii\web\View::POS_READY);
?>

<div class="fuel-delivery">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'f_pjax']); ?>
		<?php $form = ActiveForm::begin(['id' => 'fuelDelivery', 'fieldConfig' => ['template' => '{input}']]); ?>

		    <div id="section_block" style="display:none;">
		    	<?if (count($model->remove_section) > 0):?>
		    		<?foreach($model->remove_section as $id_section_remove):?>
		    			<?= $form->field($model, 'remove_section[]')->hiddenInput(['value' => $id_section_remove]) ?>
		    		<?endforeach;?>
		    	<?endif;?>
		    </div>
		    <?= $form->field($model, 'id_fuel_module')->dropDownList($model->fuelModules, ['prompt' => 'Выберите топливный модуль', 'class' => "form-control select2", 'id' => 'fuel_module_select']) ?>

		    <?if ($model->fuelModuleSections):?>

		    	<?= $form->field($model, 'id_product')->dropDownList($model->products, ['prompt' => 'Выберите продукт', 'class' => "form-control select2", 'id' => 'product_select']) ?>
		    	
		    	<?if (count($model->fuelModuleSections) > 1):?>
		    		<?= $form->field($model, 'id_fuel_module_section')->dropDownList($model->fuelModuleSections, ['prompt' => 'Выберите секцию топливного модуля', 'class' => "form-control select2", 'id' => 'fuel_module_section_select']) ?>
		    	<?else:?>
		    		<?= $form->field($model, 'id_fuel_module_section')->hiddenInput(['value' => $model->fuelModuleSections]) ?>
		    	<?endif;?>

		    	<?= $form->field($model, 'id_trailer')->dropDownList($model->trailers, ['prompt' => 'Выберите прицеп', 'class' => "form-control select2", 'id' => 'select_inp']) ?>

		    	<?= $form->field($model, 'driver')->widget(Typeahead::classname(), ['dataset' => [['local' => $model->drivers, 'limit' => 10]],'pluginOptions' => ['highlight' => true],'options' => ['placeholder' => 'Укажите Фамилию и инициалы водителя']]);?>
	                           
				    <?if ($model->sections):?>
				    	<?foreach ($model->sections as $section):?>
					    	<div class="col-md-4" data-id="<?=$section->id?>">
						    	<table class="table table-bordered table-hover">
						    		<tbody>
							    		<tr>
					                        <td align="center"><b><?=$section->name?></b></td>
					                        <td rowspan="6" align="center"><a href="#" class="sect_class"><i class="fa fa-remove" style="color: red;"></i></a></td>
					                    </tr>
					                    <tr>
					                        <td><?=$form->field($model, "volume[$section->id]")->textInput(['placeholder' => 'Долив в литрах']);?></td>
					                    </tr>
					                    <tr>
					                        <td><?=$form->field($model, "density[$section->id]")->widget(MaskedInput::className(), ['mask' => '0.999'])->textInput(['placeholder' => 'Плотность']);?></td>
					                    </tr>
					                    <tr>
					                        <td><?=$form->field($model, "temp[$section->id]")->textInput(['placeholder' => 'Температура']);?></td>
					                    </tr>
					                    <tr>
					                        <td><?=$form->field($model, "mass[$section->id]")->widget(MaskedInput::className(), ['mask' => '99.999'])->textInput(['placeholder' => 'Масса']);?></td>
					                    </tr>
					                    <tr>
					                        <td><?=$form->field($model, "pipe[$section->id]")->checkbox();?></td>
					                    </tr>
				                    </tbody>
				                </table>
				            </div>
				    	<?endforeach;?>
						<table class="table table-bordered table-hover">
					    	 <tfooter>
					    	 	<tr>
					    	 		<td colspan='6'>
					    	 			<div class="form-group">
									        <?= Html::submitButton('Продолжить', ['class' => 'btn btn-success btn-block', 'name' => 'nextButton']) ?>
									    </div>
					    	 		</td>
					    	 	</tr>
					    	 	<tr>
					    	 		<td colspan='6'>
					    	 			<div class="form-group">
									        <?= Html::submitButton('Восстановить все секции', ['class' => 'btn btn-danger btn-block', 'name' => 'resetRemoveSection']) ?>
									    </div>
					    	 		</td>
					    	 	</tr>
					    	 </tfooter>
					    </table>
				   	<?else:?>
					   	<table class="table table-bordered table-hover">	
					   		<tbody>
						    		<tr>
				                        <td colspan="6">Нет ни одной секции</td>
				                    </tr>
					    	 </tbody>
					    <?endif;?>
					</table>
		    <?endif;?>

	    <?php ActiveForm::end(); ?>
	<?php Pjax::end(); ?>

</div>

