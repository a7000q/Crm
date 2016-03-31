<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DataTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "../assets2/global/plugins/datatables/datatables.min.css",
        "../assets2/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
    ];
    public $js = [
    	"../assets2/global/scripts/datatable.js",
        '../assets2/global/plugins/datatables/datatables.min.js',
        "../assets2/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js",
        '//cdn.datatables.net/plug-ins/1.10.11/sorting/date-de.js'
    ];
    public $depends = [
       
    ];
}
