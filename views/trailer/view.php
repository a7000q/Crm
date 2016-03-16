<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\assets\UIConfirmationsAsset;
UIConfirmationsAsset::register($this); 

/* @var $this yii\web\View */
/* @var $model app\models\Trailers */

$this->title = $model->gos_number;
$this->params['breadcrumbs'][] = ['label' => 'Прицепы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trailers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(['action' => ['delete', 'id' => $model->id],  'enableClientValidation' => false]); ?>
            <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <button class="btn btn-danger" data-toggle="confirmation" data-original-title="Вы уверены что хотите удалить данный элемент?" title="">Удалить</button>
            <?= Html::a('Секции', ['sections/index', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'gos_number',
        ],
    ]) ?>
    
</div>
