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
class DatePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "../assets2/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css",
        "../assets2/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css",
        "../assets2/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
        "../assets2/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css",
        "../assets2/global/plugins/clockface/css/clockface.css",
    ];
    public $js = [
    	"../assets2/global/plugins/moment.min.js",
        '../assets2/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
        "../assets2/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
        "../assets2/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
        "../assets2/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js",
        "../assets2/global/plugins/clockface/js/clockface.js"
    ];
    public $depends = [
       
    ];
}
