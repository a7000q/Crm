<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

use app\assets\DataTableAsset;
DataTableAsset::register($this);

use app\assets\Select2Asset;
Select2Asset::register($this); 

$this->title = "Отчет по ошибкам";
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
                [-1],
                ['All'] // change per page values here
            ],
            'pageLength': -1,            
            'pagingType': 'bootstrap_full_number',
            'order': [
                [0, 'asc']
            ] // set first column as a default sort by asc
        });
        ", yii\web\View::POS_READY);
?>

<div class="report-bay">

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

    	<table class="table table-bordered table-hover" id="dataTable">
    		<thead>
    			<td><b>Дата</b></td>
    			<td><b>ТРК</b></td>
    			<td><b>Топливный модуль</b></td>
    			<td><b>Текст ошибки</b></td>
    		</thead>
            <?if (count($model->dataReport) > 1):?>
    	    	<?foreach ($model->dataReport as $report):?>
    	    		<tr>
    	    			<td><?=$report["date"]?></td>
    	    			<td><?=$report["terminal"]?></td>
    	    			<td><?=$report["module"]?></td>
    	    			<td><?=$report["text"]?></td>
    	    		</tr>
    	    	<?endforeach;?>
            <?endif;?>
    	</table>
    <?php ActiveForm::end(); ?>

</div>