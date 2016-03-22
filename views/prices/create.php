<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Prices */

$this->title = 'Добавление прайса для '.$model->partner->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['partners/index']];
$this->params['breadcrumbs'][] = ['label' => 'Прайс '.$model->partner->name, 'url' => ['index', "id" => $model->id_partner]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
