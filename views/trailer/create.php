<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Trailers */

$this->title = 'Новый прицеп';
$this->params['breadcrumbs'][] = ['label' => 'Прицепы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trailers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
