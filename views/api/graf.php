<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?if (isset($data["h"])):?>
	<div>
		H: <?=$data["h"]?> - <?=$data["litr"]?>
	</div>
<?endif;?>

<?
	$litr = 0;
?>
<script>
	$(function () {
	    $('#container').highcharts({
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
	            	<?foreach ($TestCalibr as $calibr):?>
	            		<?$litr = $calibr->litr?>
            			[<?=$litr?>, <?=$calibr->h?>], 
            		<?endforeach;?>
	            	]
	        }]
	    });
	});
</script>