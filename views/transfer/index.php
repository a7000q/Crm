<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Перемещения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trailers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
        <table class="table table-bordered table-hover" style="width: 100%;">
            <tr>
                <td><b>Дата</b></td>
                <td><b>Карта, №</b></td>
                <td><b>Имя</b></td>
                <td><b>Литры, л</b></td>
                <td><b>Топивный модуль</b></td>
                <td><b>Действия</b></td>
            </tr>
            <?foreach ($model as $tranz):?>
                <tr>
                    <td><?=$tranz->dateText;?></td>
                    <td><?=$tranz->card->id_txt;?></td>
                    <td><?=$tranz->card->name;?></td>
                    <td><?=$tranz->doza;?></td>
                    <td><?=$tranz->section->module->name;?></td>
                    <td><a href="<?=Url::toRoute(['transfer/transfer-save', 'id' => $tranz->id])?>" class="btn btn-success">Провести</a></td>
                </tr>
            <?endforeach;?>
        </table>
    <?php ActiveForm::end(); ?>

</div>
