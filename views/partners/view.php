<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\assets\UIConfirmationsAsset;
UIConfirmationsAsset::register($this); 
/* @var $this yii\web\View */
/* @var $model app\models\Partners */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(['action' => ['delete', 'id' => $model->id],  'enableClientValidation' => false]); ?>
            <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <button class="btn btn-danger" data-toggle="confirmation" data-original-title="Вы уверены что хотите удалить данный элемент?" title="">Удалить</button>
            <?= Html::a('Расчетные счета', ['bank-accounts/index', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Прайс', ['prices/index', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'inn',
            'full_name',
            'address',
            'fakt_address',
            'pravo_forma',
            'name',
            'kpp',
            'ogrn',
            'okved',
            'okato',
            'oktmo',
            'okogu',
            'okfs',
            'okopf',
            'okpo',
            'email:email',
            'phone',
            'phoneSms',
            'director',
            'osnovanie',
            'limit'
        ],
    ]) ?>

</div>
