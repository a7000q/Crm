<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Потверждение введенных данных';

?>

<div class="main step2" style="overflow:hidden;">

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-bordered table-hover">
    	<tr>
    		<td><b>Дата</b></td>
    		<td><?=date("d.m.y", $model->date)?></td>
    	</tr>
    	<tr>
    		<td><b>Адрес</b></td>
    		<td><?=$model->address?></td>
    	</tr>
    	<tr>
    		<td><b>Водитель</b></td>
    		<td><?=$model->driver?></td>
    	</tr>
    	<tr>
    		<td><b>Прицеп</b></td>
    		<td><?=$model->trailer->gos_number?></td>
    	</tr>
    	<tr>
    		<td><b>Продукт</b></td>
    		<td><?=$model->product->name?></td>
    	</tr>
    	<tr>
    		<td><b>Приемщик</b></td>
    		<td><?=$model->userName?></td>
    	</tr>
    </table>

	<h2>Секции</h2>

	<?if($model->activeSections):?>
		<?foreach ($model->activeSections as $section):?>
			<div class="col-md-4">
				<table class="table table-bordered table-hover">
					<tr>
						<td><b>Номер отсека</b></td>
						<td><?=$section->name?></td>
					</tr>
					<tr>
						<td><b>Плотность</b></td>
						<td><?=$section->density?></td>
					</tr>
					<tr>
						<td><b>Температура</b></td>
						<td><?=$section->temp?></td>
					</tr>
					<tr>
						<td><b>Калибровка, л</b></td>
						<td><?=$section->kalibr?></td>
					</tr>
					<tr>
						<td><b>Долив, л</b></td>
						<td><?=$section->volume?></td>
					</tr>
					<tr>
						<td><b>Фактический объем, л</b></td>
						<td><?=$section->fakt_volume?></td>
					</tr>
					<tr>
						<td><b>Количество тонн по накладной</b></td>
						<td><?=$section->mass?></td>
					</tr>
					<tr>
						<td><b>Количество тонн факт</b></td>
						<td><?=$section->fakt_mass?></td>
					</tr>
					<tr>
						<td><b><?if ($section->diff_mass >= 0):?>Излишек<?else:?>Недосдача<?endif;?> в кг.</b></td>
						<td><?=abs($section->diff_mass)?></td>
					</tr>
				</table>
			</div>
		<?endforeach;?>
		<div class="col-md-12">
			<h2>Итоговые данные</h2>
			<table class="table table-bordered table-hover">
				<tr>
					<td><b>Калибровка, л</b></td>
					<td><?=$model->FuelDelivery->kalibr?></td>
				</tr>
				<tr>
					<td><b>Долив, л</b></td>
					<td><?=$model->FuelDelivery->volume?></td>
				</tr>
				<tr>
					<td><b>Фактический объем, л</b></td>
					<td><?=$model->FuelDelivery->fakt_volume?></td>
				</tr>
				<tr>
					<td><b>Количество тонн по накладной</b></td>
					<td><?=$model->FuelDelivery->mass?></td>
				</tr>
				<tr>
					<td><b>Количество тонн факт</b></td>
					<td><?=$model->FuelDelivery->fakt_mass?></td>
				</tr>
				<tr>
					<td><b><?if ($model->FuelDelivery->diff_mass >= 0):?>Излишек<?else:?>Недосдача<?endif;?> в кг.</b></td>
					<td><?=abs($model->FuelDelivery->diff_mass)?></td>
				</tr>
			</table>
		</div>
	<?endif;?>
	<?php $form = ActiveForm::begin(); ?>
		<table class="table table-bordered table-hover">
	    	 <tfooter>
	    	 	<tr>
	    	 		<td>
	    	 			<div class="form-group">
					        <?= Html::submitButton('Потвердить', ['class' => 'btn btn-success btn-block', 'name' => 'saveData']) ?>
					    </div>
	    	 		</td>
	    	 	</tr>
	    	 	<tr>
	    	 		<td>
	    	 			<div class="form-group">
					        <?= Html::submitButton('Вернуться', ['class' => 'btn btn-danger btn-block', 'name' => 'prevButton']) ?>
					    </div>
	    	 		</td>
	    	 	</tr>
	    	 </tfooter>
	    </table>
    <?php ActiveForm::end(); ?>
</div>