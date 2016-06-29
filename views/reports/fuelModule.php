<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

$this->title = "Отчет по топливным модулям";
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('#d1').datetimepicker({
            language: 'ru',
            format: 'dd.mm.yyyy',
            minView: 2
        });
        ", yii\web\View::POS_READY);
?>

<div class="report-fuel-module">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?=Html::input('text', 'dateSearch', date("d.m.Y", $date), ['id' => 'd1'])?>

        <button>Применить</button>

        <br/> <br/> <br/>
    	
        <table class="table table-bordered table-hover" id="dataTable">
    		<thead>
    			<td><b>Топливный модуль</b></td>
    			<td><b>Приход</b></td>
                <td><b>Расход</b></td>
                <td><b>Остаток</b></td>
    		</thead>
	    	<?foreach ($modules as $module):?>
	    		<tr>
	    			<td><?=$module->name?></td>
	    			<td><?=$module->getComingSum($date)?></td>
	    			<td><?=$module->getSaleSum($date)?></td>
	    			<td><?=$module->getTBalance($date)?></td>
	    		</tr>
	    	<?endforeach;?>
    	</table>
    <?php ActiveForm::end(); ?>

</div>