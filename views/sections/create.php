<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sections */

$this->title = 'Создание секции';
$this->params['breadcrumbs'][] = ['label' => 'Прицепы', 'url' => ['trailer/index']];
$this->params['breadcrumbs'][] = ['label' => 'Секции', 'url' => ['index', 'id' => $model->id_trailer]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
