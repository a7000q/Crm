<?
    use yii\helpers\Url;
?>

<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
    <li class="sidebar-toggler-wrapper hide">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler"> </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
    <li class="nav-item">
        <a href="/" class="nav-link nav-toggle">
            <i class="fa fa-filter"></i>
            <span class="title">Поступление топлива</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?=Url::toRoute('trailer/index')?>" class="nav-link nav-toggle">
            <i class="fa fa-truck"></i>
            <span class="title">Прицепы</span>
        </a>
    </li>
</ul>