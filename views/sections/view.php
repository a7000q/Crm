<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\assets\UIConfirmationsAsset;
UIConfirmationsAsset::register($this); 
/* @var $this yii\web\View */
/* @var $model app\models\Sections */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Прицепы', 'url' => ['trailer/index']];
$this->params['breadcrumbs'][] = ['label' => 'Секции', 'url' => ['index', 'id' => $model->id_trailer]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sections-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(['action' => ['delete', 'id' => $model->id],  'enableClientValidation' => false]); ?>
            <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <button class="btn btn-danger" data-toggle="confirmation" data-original-title="Вы уверены что хотите удалить данный элемент?" title="">Удалить</button>
        <?php ActiveForm::end(); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'volume',
            'volume_pipe',
        ],
    ]) ?>

</div>
