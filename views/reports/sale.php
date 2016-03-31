<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

use app\assets\DataTableAsset;
DataTableAsset::register($this);

$this->title = "Отчет по продажам";
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('#d1, #d2').datetimepicker({
            language: 'ru',
            format: 'dd.mm.yyyy',
            minView: 2
        });
        var table = $('#dataTable');

        table.dataTable({
            'language': {
                'aria': {
                    'sortAscending': ': activate to sort column ascending',
                    'sortDescending': ': activate to sort column descending'
                },
                'emptyTable': 'No data available in table',
                'info': 'Showing _START_ to _END_ of _TOTAL_ records',
                'infoEmpty': 'No records found',
                'infoFiltered': '(filtered1 from _MAX_ total records)',
                'lengthMenu': 'Show _MENU_',
                'search': 'Search:',
                'zeroRecords': 'No matching records found',
                'paginate': {
                    'previous':'Prev',
                    'next': 'Next',
                    'last': 'Last',
                    'first': 'First'
                }
            },

            'bStateSave': true, // save datatable state(pagination, sort, etc) in cookie.

            'columnDefs': [ {
                type: 'de_datetime',
                targets: 0,
            }],

            'lengthMenu': [
                [5, 15, 20, -1],
                [5, 15, 20, 'All'] // change per page values here
            ],
            'pageLength': 5,            
            'pagingType': 'bootstrap_full_number',
            'order': [
                [0, 'asc']
            ] // set first column as a default sort by asc
        });
        ", yii\web\View::POS_READY);
?>




<div class="report-sale">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php $form = ActiveForm::begin(); ?>
        <p>
            <b>Фильтр: </b>

            <table class="table table-bordered table-hover" style="width: 50%;">
            <tr>
                <td><?= $form->field($model, 'd1')->textInput(['class' => 'form-control form-control-inline input-medium date-picker', 'id' => 'd1'])?></td>
                <td><?= $form->field($model, 'd2')->textInput(['class' => 'form-control form-control-inline input-medium date-picker', 'id' => 'd2'])?></td>
            </tr>
            <tr>
                <td colspan="2"><?= Html::submitButton('Применить', ['class' => 'btn btn-success btn-block', 'name' => 'filterButton']) ?></td>
            </tr>
            
            
            </table>
        </p>
    	<table class="table table-striped table-bordered table-hover table-checkable order-column" id="dataTable">
    		<thead>
    			<td><b>Дата</b></td>
    			<td><b>Номер карты</b></td>
    			<td><b>Имя</b></td>
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
	    			<td><?=$report["cardNumber"]?></td>
	    			<td><?=$report["name"]?></td>
	    			<td><?=$report["company"]?></td>
	    			<td><?=$report["fuel_module"]?></td>
	    			<td><?=number_format($report["litr"], 2, ",", " ")?></td>
	    			<td><?=$report["product_name"]?></td>
	    			<td><?=$report["price"]?></td>
	    			<td><?=$report["sum"]?></td>
	    		</tr>
	    	<?endforeach;?>
    	</table>
    <?php ActiveForm::end(); ?>

</div>


