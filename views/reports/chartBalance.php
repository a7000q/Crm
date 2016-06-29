<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use app\assets\DatePickerAsset;
DatePickerAsset::register($this);

use app\assets\Select2Asset;
Select2Asset::register($this); 

$this->title = "График движения остатков";
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$('#d1, #d2').datetimepicker({
            language: 'ru',
            format: 'dd.mm.yyyy',
            minView: 2
        });
        ", yii\web\View::POS_READY);



if ($model->data)
{
    $txt = "$('#container').highcharts({
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Движение'
                },
                yAxis: {
                    title: {
                        text: 'Остаток'
                    }
                },
                plotOptions: {
                    series: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                data: {
                    rows:  [
                        ['date', 'litr', 'sensor'],
                        ";
                    
                    foreach ($model->data as $date => $m) 
                    {
                        $model->litrs += $m["litr"];
                        if (isset($m["sensor"]))
                            $model->sensor = $m["sensor"];
                        $txt .= "['".date("d/m/Y H-i", $date)."', ".$model->litrs.", ".$model->sensor."],";
                    }
                    

                $txt .= "]}
                });";
                        
        $this->registerJs($txt, yii\web\View::POS_READY);
}
?>

<div class="charts-balance">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
        <p>

            <table class="table table-bordered table-hover" style="width: 100%;">
            <tr>
                <td><?= $form->field($model, 'd1')->textInput(['class' => 'form-control form-control-inline input-medium date-picker', 'id' => 'd1'])?></td>
                <td><?= $form->field($model, 'd2')->textInput(['class' => 'form-control form-control-inline input-medium date-picker', 'id' => 'd2'])?></td>
                <td><?= $form->field($model, 'module')->dropDownList($model->modules, ['id' => 'filterModule', 'class' => "form-control select2-multiple", 'placeholder' => 'Выберите модуль'])?></td>
            </tr>

            <tr>
                <td colspan="3"><?= Html::submitButton('Применить', ['class' => 'btn btn-success btn-block', 'name' => 'filterButton']) ?></td>
            </tr>
            
            
            </table>

            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            
        </p>
    <?php ActiveForm::end(); ?>
</div>


<?$this->registerJsFile('https://code.highcharts.com/highcharts.js');?>
<?$this->registerJsFile('https://code.highcharts.com/modules/data.js');?>
<?$this->registerJsFile('https://code.highcharts.com/modules/exporting.js');?>

