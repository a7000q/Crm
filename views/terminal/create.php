<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Terminals */

$this->title = 'Добавление терминала';
$this->params['breadcrumbs'][] = ['label' => 'Терминалы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="terminals-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
