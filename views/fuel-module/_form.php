<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\YandexMapAsset;
YandexMapAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\FuelModule */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile("js/fuel-module/yandexMap.js", ['position' => yii\web\View::POS_BEGIN]);
?>

<div class="fuel-module-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <div id="map" style="width: 100%; height: 500px; margin-top: 20px; margin-bottom: 20px;"></div>

    <?=$form->field($model, 'coords', ['template' => '{input}'])->hiddenInput(['id' => 'coords'])?>

    <h3>Координаты: <span id="value_coords"><?=$model->coords?></span></h3>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
	ymaps.ready(init);
    var myMap;
    var myPlacemark;
    var myCoords = "<?=$model->coords;?>";


    function init(){     
        var myGeocoder = ymaps.geocode("<?=$model->address?>", {results: 1}).then(function (res){
	    	var ad = res.geoObjects.get(0);

	    	myMap = new ymaps.Map("map", {
	            center: ad.geometry.getCoordinates(),
	            zoom: 12
	        });

	        if (myCoords != "")
	        {
	        	xx = myCoords.split(',');
	        	x1 = xx[0];
	        	x2 = xx[1];
	        	addPoint(myMap, x1, x2);
	        }

	        myMap.events.add('click', function (e) {
		        var coords = e.get('coords');
		        x1 = coords[0].toPrecision(6);
		        x2 = coords[1].toPrecision(6);

		        var coords_txt = x1 + ", " + x2;
		        $('#value_coords').html(coords_txt);
		        $('#coords').val(coords_txt);
		        addPoint(myMap, x1, x2);
		    });
	    });
    };

    function addPoint(map, x1, x2)
    {
    	myPlacemark = new ymaps.Placemark([x1, x2]);
    	map.geoObjects.removeAll();
		map.geoObjects.add(myPlacemark);
    }

</script>