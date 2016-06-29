<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
?>
<div class="add-card">
	<h1>Новая топливная карта</h1>

	<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'cardNumber')?>
		<?= $form->field($model, 'id_partner')->dropDownList($model->partners, ['prompt' => 'Выберите контрагента']) ?>
		<?= $form->field($model, 'id_txt')?>
		<?= $form->field($model, 'name')?>

		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block', 'name' => 'saveButton']) ?>
	<?php ActiveForm::end(); ?>
</div>

<?if ($success):?>
	<script>
		alert("Карта добавлена");
	</script>
<?endif;?>