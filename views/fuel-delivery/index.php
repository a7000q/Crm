<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
	use yii\widgets\Pjax;
	use app\assets\Select2Asset;
	Select2Asset::register($this); 

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

	$this->registerJs("$('#select_inp, #fuel_module_select, #fuel_module_section_select').change(function(){
						  $('#fuelDelivery').submit();
						});", yii\web\View::POS_READY);

	$this->registerJs("$('.sect_class').click(function(){
						  obj = $(this).parent('td').parent('tr');
						  id_section = $(obj).attr('data-id');
						  $('#section_block').append('<input type=\'hidden\' name=\'AddFuelDeliveryForm[remove_section][]\' value=\''+id_section+'\'>');
						  $(this).parent('td').parent('tr').remove();
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

		    	<?= $form->field($model, 'id_fuel_module_section')->dropDownList($model->fuelModuleSections, ['prompt' => 'Выберите секцию топливного модуля', 'class' => "form-control select2", 'id' => 'fuel_module_section_select']) ?>

		    	<?= $form->field($model, 'id_trailer')->dropDownList($model->trailers, ['prompt' => 'Выберите прицеп', 'class' => "form-control select2", 'id' => 'select_inp']) ?>

			    <table class="table table-bordered table-hover">
	                <thead>
	                    <tr>
	                        <th>#</th>
	                        <th>Объем, м<sup>3</sup></th>
	                        <th>Плотность, кг/м<sup>3</sup></th>
	                        <th>Температура, <sup>o</sup>C</th>
	                        <th>Масса, кг</th>
	                        <th><i class="fa fa-minus-square"></i></th>
	                    </tr>
	                </thead>
	                           
				    <?if ($model->sections):?>
					    <tbody>
					    	<?foreach ($model->sections as $section):?>
					    		<tr data-id="<?=$section->id?>">
			                        <td><?=$section->name?></td>
			                        <td><?=$form->field($model, "volume[$section->id]");?></td>
			                        <td><?=$form->field($model, "density[$section->id]");?></td>
			                        <td><?=$form->field($model, "temp[$section->id]");?></td>
			                        <td><?=$form->field($model, "mass[$section->id]");?></td>
			                        <td><a href="#" class="sect_class"><i class="fa fa-remove" style="color: red;"></i></a></td>
			                    </tr>
					    	<?endforeach;?>
				    	 </tbody>
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
				   	<?else:?>
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

