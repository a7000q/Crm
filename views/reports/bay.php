<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

$this->title = "Отчет по поступлениям";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-bay">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    	<table class="table table-bordered table-hover">
    		<thead>
    			<td><b>Дата</b></td>
    			<td><b>Гос. номер</b></td>
    			<td><b>Компания</b></td>
    			<td><b>Топливный модуль</b></td>
    			<td><b>Литры</b></td>
    			<td><b>Тип топлива</b></td>
    			<td><b>Цена за литр</b></td>
    			<td><b>Сумма</b></td>
    		</thead>
	    	<?foreach ($model->dataReport as $report):?>
	    		<tr>
	    			<td><?=$report["date"]?></td>
	    			<td><?=$report["gos_number"]?></td>
	    			<td><?=$report["company"]?></td>
	    			<td><?=$report["fuel_module"]?></td>
	    			<td><?=$report["litr"]?></td>
	    			<td><?=$report["product_name"]?></td>
	    			<td><?=$report["price"]?></td>
	    			<td><?=$report["sum"]?></td>
	    		</tr>
	    	<?endforeach;?>
    	</table>
    <?php ActiveForm::end(); ?>

</div>