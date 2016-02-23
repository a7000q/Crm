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
class Select2Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "../assets2/global/plugins/select2/css/select2.min.css",
        "../assets2/global/plugins/select2/css/select2-bootstrap.min.css"
    ];
    public $js = [
    	"../assets2/global/plugins/select2/js/select2.full.min.js",
        '../assets2/global/scripts/app.min.js',
        "../assets2/pages/scripts/components-select2.min.js"
    ];
    public $depends = [
       
    ];
}
