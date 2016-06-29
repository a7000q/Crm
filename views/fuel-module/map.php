<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\YandexMapAsset;
YandexMapAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\FuelModule */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Карта топливных модулей';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="fuel-module-map">

	<h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    	<div id="map" style="width: 100%; height: 75vh; margin-top: 20px; margin-bottom: 20px;"></div>

    <?php ActiveForm::end(); ?>

</div>

<script>
	ymaps.ready(init);
    var myMap;
    var myPlacemark = [];


    function init() 
    {     
        myMap = new ymaps.Map("map", {
            center: [55.76, 37.64],
            zoom: 5
        });

        var i = 0;

        <?foreach ($modules as $module):?>
			var myCoords = "<?=$module->coords?>"; 
        	var xx = myCoords.split(',');
        	var x1 = xx[0];
        	var x2 = xx[1];
        	var hint = "<?=$module->name;?>";
        	var balloon = "<h3><?=$module->name;?></h3><?=$module->address?>";
        	addPoint(myMap, x1, x2, hint, balloon, i);
        	i++;
        <?endforeach;?>

        var myClusterer = new ymaps.Clusterer({preset: 'islands#invertedDarkGreenClusterIcons'});
        myClusterer.add(myPlacemark);
        myMap.geoObjects.add(myClusterer);
    };

    function addPoint(map, x1, x2, hintContent, balloonContent, i)
    {
    	myPlacemark[i] = new ymaps.Placemark([x1, x2], {balloonContent: balloonContent, iconContent: hintContent}, {preset: 'islands#darkGreenStretchyIcon'});
    }

</script>