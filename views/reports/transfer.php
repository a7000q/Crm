<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

use app\assets\DataTableAsset;
DataTableAsset::register($this);

use app\assets\Select2Asset;
Select2Asset::register($this); 

$this->title = "Отчет по перемещениям";
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('#filterModule').select2({
          placeholder: 'Выберите модуль'
        });", yii\web\View::POS_READY);

$this->registerJs("$('#d1, #d2').datepicker({
            language: 'ru',
            format: 'dd.mm.yyyy',
            minView: 2
        });
        var table = $('#dataTable');

        table.dataTable({
            paging: false,
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
                'search': 'Поиск:',
                'zeroRecords': 'No matching records found'
            },

            'bStateSave': true, // save datatable state(pagination, sort, etc) in cookie.

            'columnDefs': [ {
                type: 'de_datetime',
                targets: 0,
            }],
            'order': [
                [0, 'asc']
            ], // set first column as a default sort by asc
            'footerCallback': function(row, data, start, end, display){
                var api = this.api(), data;

                var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '.')/1*1 :
                    typeof i === 'number' ?
                        i : 0;
                };

                pageTotalLitr = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
                $( api.column( 5 ).footer() ).html(
                    '<b>'+pageTotalLitr+'</b>'
                );
            }
        });
        ", yii\web\View::POS_READY);
?>




<div class="report-transfer">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php $form = ActiveForm::begin(); ?>
        <p>
            <b>Фильтр: </b>

            <table class="table table-bordered table-hover" style="width: 100%;">
            <tr>
                <td><?= $form->field($model, 'd1')->textInput(['class' => 'form-control form-control-inline input-medium date-picker', 'id' => 'd1'])?></td>
                <td><?= $form->field($model, 'd2')->textInput(['class' => 'form-control form-control-inline input-medium date-picker', 'id' => 'd2'])?></td>
                <td><?= $form->field($model, 'module')->dropDownList($model->modules, ['id' => 'filterModule', 'class' => "form-control select2-multiple", "multiple" => true, 'placeholder' => 'Выберите модуль'])?></td>
            </tr>

            <tr>
                <td colspan="4"><?= Html::submitButton('Применить', ['class' => 'btn btn-success btn-block', 'name' => 'filterButton']) ?></td>
            </tr>
            
            
            </table>
        </p>
    	<table class="table table-striped table-bordered table-hover table-checkable order-column" id="dataTable">
    		<thead>
                <td><b>ID</b></td>
    			<td><b>Дата</b></td>
                <td><b>Карта</b></td>
    			<td><b>Имя</b></td>
    			<td><b>Топл. модуль (откуда)</b></td>
                <td><b>Топл. модуль (куда)</b></td>
    			<td><b>Литры</b></td>
    		</thead>
            <?if ($model->dataReport):?>
                <tbody>
        	    	<?foreach ($model->dataReport as $report):?>
        	    		<tr>
                            <td><?=$report["id"]?></td>
                            <td><?=$report["date"]?></td>
                            <td><?=$report["cardNumber"]?></td>
        	    			<td><?=$report["name"]?></td> 
        	    			<td><?=$report["fuel_module_start"]?></td>
                            <td><?=$report["fuel_module_end"]?></td>
        	    			<td style="text-align: right;"><?=number_format($report["litr"], 2, ",", " ")?></td>
        	    		</tr>
        	    	<?endforeach;?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><b>Итого:</b></td>
                        <td style="text-align: right;"><b><?=number_format($model->sumLitr, 2, ".", " ")?></b></td>
                    </tr>
                </tfoot>
            <?else:?>
                <tr>
                    <td colspan="6">
                        Нет результатов
                    </td>
                </tr>
            <?endif;?>

    	</table>
    <?php ActiveForm::end(); ?>

</div>


