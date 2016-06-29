<?
    use yii\helpers\Url;
?>

<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
    <li class="sidebar-toggler-wrapper hide">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler"> </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'fuel-delivery', 'a' => 'index'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('fuel-delivery/index')?>" class="nav-link nav-toggle">
                <i class="fa fa-filter"></i>
                <span class="title">Поступление топлива</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'inpayment', 'a' => '*'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('inpayment/index')?>" class="nav-link nav-toggle">
                <span class="title">Оплаты</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'invoice', 'a' => '*'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('invoice/index')?>" class="nav-link nav-toggle">
                <span class="title">Счета на оплату</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'fuel-delivery', 'a' => 'sale-fuel'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('fuel-delivery/sale-fuel')?>" class="nav-link nav-toggle">
                <span class="title">Продажа товара</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'trailer', 'a' => 'index'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('trailer/index')?>" class="nav-link nav-toggle">
                <i class="fa fa-truck"></i>
                <span class="title">Прицепы</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'products', 'a' => 'index'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('products/index')?>" class="nav-link nav-toggle">
                <span class="title">Продукты</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'fuel-module', 'a' => 'index'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('fuel-module/index')?>" class="nav-link nav-toggle">
                <span class="title">Топливные модули</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'partners', 'a' => 'index'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('partners/index')?>" class="nav-link nav-toggle">
                <span class="title">Контрагенты</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'fuel-delivery', 'a' => 'comings'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('fuel-delivery/comings')?>" class="nav-link nav-toggle">
                <span class="title">Приход товара</span>
            </a>
        </li>
    <?endif;?>
    <li class="nav-item">
        <a href="#" class="nav-link nav-toggle">
            <span class="title">Отчеты</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
            <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'reports', 'a' => 'sale'])):?>
                <li class="nav-item start ">
                    <a href="<?=Url::toRoute('reports/sale')?>" class="nav-link ">
                        <span class="title">Продажи</span>
                    </a>
                </li>
            <?endif;?>
            <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'reports', 'a' => 'bay'])):?>
                <li class="nav-item start ">
                    <a href="<?=Url::toRoute('reports/bay')?>" class="nav-link ">
                        <span class="title">Поступления</span>
                    </a>
                </li>
            <?endif;?>
            <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'reports', 'a' => 'transfer'])):?>
                <li class="nav-item start ">
                    <a href="<?=Url::toRoute('reports/transfer')?>" class="nav-link ">
                        <span class="title">Перемещения</span>
                    </a>
                </li>
            <?endif;?>
            <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'reports', 'a' => 'fuel-module'])):?>
                <li class="nav-item start ">
                    <a href="<?=Url::toRoute('reports/fuel-module')?>" class="nav-link ">
                        <span class="title">Остатки по модулям</span>
                    </a>
                </li>
            <?endif;?>
        </ul>
    </li>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'cards', 'a' => 'bad-cards'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('cards/bad-cards')?>" class="nav-link nav-toggle">
                <span class="title">Неопознанные карты</span>
            </a>
        </li>
    <?endif;?>
     <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'api', 'a' => 'test-graf'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('api/test-graf')?>" class="nav-link nav-toggle">
                <span class="title">График калибровки</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'terminal', 'a' => '*'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('terminal/index')?>" class="nav-link nav-toggle">
                <span class="title">Терминалы</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'reports', 'a' => 'error-terminals'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('reports/error-terminals')?>" class="nav-link nav-toggle">
                <span class="title">Отчеты об ошибках</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'transfer', 'a' => '*'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('transfer/index')?>" class="nav-link nav-toggle">
                <span class="title">Перемещения</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'fuel-module', 'a' => 'map'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('fuel-module/map')?>" class="nav-link nav-toggle">
                <span class="title">Карта топливных модулей</span>
            </a>
        </li>
    <?endif;?>
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'cards', 'a' => 'add-card'])):?>
        <li class="nav-item">
            <a href="<?=Url::toRoute('cards/add-card')?>" class="nav-link nav-toggle">
                <span class="title">Добавить карту</span>
            </a>
        </li>
    <?endif;?>
</ul>