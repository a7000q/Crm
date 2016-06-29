<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;


$this->title = "Детальный просмотр транзакции №".$data["id"];
$this->params['breadcrumbs'][] = ['label' => 'Отчет по продажам', 'url' => ['reports/sale']];;
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="report-sale">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php $form = ActiveForm::begin(); ?>
    	<table class="table table-striped table-bordered table-hover table-checkable order-column" id="dataTable">
    		<tr>
    			<td><b>ID</b></td>
                <td><?=$data["id"]?></td>
            </tr>
            <tr>
                <td><b>Дата</b></td>
                <td><?=$data["date"]?></td>
            </tr>
            <tr>
                <td><b>Номер карты</b></td>
                <td><?=$data["cardNumber"]?></td>
            </tr>
             <tr>
                <td><b>Имя</b></td>
                <td><?=$data["name"]?></td>
            </tr>
             <tr>
                <td><b>Компания</b></td>
                <td><?=$data["company"]?></td>
            </tr>
             <tr>
                <td><b>Модуль</b></td>
                <td><?=$data["fuel_module"]?></td>
            </tr>
             <tr>
                <td><b>Литры</b></td>
                <td><?=$data["litr"]?></td>
            </tr>
             <tr>
                <td><b>Продукт</b></td>
                <td><?=$data["product_name"]?></td>
            </tr>
             <tr>
                <td><b>Цена за литр</b></td>
                <td><?=$data["price"]?></td>
            </tr>
             <tr>
                <td><b>Сумма</b></td>
                <td><b><?=$data["sum"]?></b></td>
            </tr>
    	</table>
    <?php ActiveForm::end(); ?>

</div>


