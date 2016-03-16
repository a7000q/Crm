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
    <? if (Yii::$app->user->identity->isPermissionAction(['c' => 'reports', 'a' => '*'])):?>
        <li class="nav-item">
            <a href="#" class="nav-link nav-toggle">
                <span class="title">Отчеты</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item start ">
                    <a href="<?=Url::toRoute('reports/sale')?>" class="nav-link ">
                        <span class="title">Продажи</span>
                    </a>
                </li>
                <li class="nav-item start ">
                    <a href="<?=Url::toRoute('reports/bay')?>" class="nav-link ">
                        <span class="title">Поступления</span>
                    </a>
                </li>
            </ul>
        </li>
    <?endif;?>
</ul>