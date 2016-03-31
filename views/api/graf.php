<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;

	$txt = "$('#container').highcharts({
	        chart: {
	            type: 'line'
	        },
	        title: {
	            text: 'Калибровка'
	        },
	        yAxis: {
	            title: {
	                text: 'Высота'
	            }
	        },
	        plotOptions: {
	            line: {
	                dataLabels: {
	                    enabled: true
	                },
	                enableMouseTracking: false
	            }
	        },
	        series: [{
	            name: 'Литры',
	            data: [
	            	";
	        foreach ($TestCalibr as $calibr) {
        		$litr = $calibr->litr;
        		$txt .= "[$litr, $calibr->h],";
    		}

    		$txt .= "]
		        }]
		    });";
	            	
	$this->registerJs($txt, yii\web\View::POS_READY);


?>

<?php $form = ActiveForm::begin(); ?>

<?php ActiveForm::end(); ?>


<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?if (isset($data["h"])):?>
	<div>
		H: <?=$data["h"]?> - <?=$data["litr"]?>
	</div>
<?endif;?>

<?$this->registerJsFile('https://code.highcharts.com/highcharts.js');?>
<?$this->registerJsFile('https://code.highcharts.com/modules/exporting.js');?>

<?
	$litr = 0;
?>
