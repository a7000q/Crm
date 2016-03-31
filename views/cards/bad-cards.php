<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<div class="fuel-delivery">
	<h1>Список неопознанных карт</h1>

	<?php $form = ActiveForm::begin(); ?>
		<table class="table table-bordered table-hover">
			<tr>
				<td>Дата</td>
				<td>Терминал</td>
				<td>Топливный модуль</td>
				<td>id_electro</td>
				<td colspan="2" align="center">Действия</td>
			</tr>
			<?foreach ($cards as $card):?>
				<tr>
					<td><?=date("d.m.Y H:i", $card->date)?></td>
					<td><?=$card->terminal->name?></td>
					<td><?=$card->terminal->fuelModule->name?></td>
					<td><?=$card->id_electro?></td>
					<td align="center"><a href="<?=Url::toRoute(['cards/delete-bad-tranzaction', 'id' => $card->id])?>">Удалить</a></td>
					<td align="center"><a href="<?=Url::toRoute(['cards/create-new-card', 'id_electro' => $card->id_electro])?>">Добавить в базу</a></td>
				</tr>
			<?endforeach;?>
		</table>
	<?php ActiveForm::end(); ?>
</div>
