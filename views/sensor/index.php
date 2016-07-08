<?php
	use yii\widgets\Pjax;
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\helpers\Url;

	$this->title = 'Уровнемеры';
	$this->params['breadcrumbs'][] = $this->title;

	$script = <<< JS
		$(document).ready(function() {
		    setInterval(function(){  $.pjax.reload({container:"#sensorsBlock"}); }, 3000);
		});
JS;
	$this->registerJs($script, 4);
?>
<div class="sensors-level">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php Pjax::begin(['id' => 'sensorsBlock']); ?>

		<?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'columns' => [
	            'name',
	            'statusDate',
	            'fuelModuleSection.module.name',
	            'h',
	            'density',
	            'temp',
	            'water_level'
	        ]
	    ]); ?>

	<?php Pjax::end(); ?>

</div>


