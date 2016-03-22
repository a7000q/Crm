<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\assets\UIConfirmationsAsset;
UIConfirmationsAsset::register($this); 

/* @var $this yii\web\View */
/* @var $model app\models\Prices */

$this->title = $model->product->short_name." для ".$model->partner->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = ['label' => 'Прайс '.$model->partner->name, 'url' => ['index', "id" => $model->id_partner]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prices-view">

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
            'partner.name',
            'product.short_name',
            'type.name',
            'price',
            [
                'attribute' => 'fileSrc',
                'format'=>'raw'
            ]
        ],
    ]) ?>

</div>
