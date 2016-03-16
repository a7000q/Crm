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
class InputMaskAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      
    ];
    public $js = [ 
    	'../assets2/inputmask/jquery.inputmask.js',
        '../assets2/inputmask/jquery.inputmask.extensions.js',
        '../assets2/inputmask/jquery.inputmask.date.extensions.js',
        '../assets2/inputmask/jquery.inputmask.numeric.extensions.js',
        '../assets2/inputmask/jquery.inputmask.custom.extensions.js'
    ];
    public $depends = [
       //'yii\web\JqueryAsset'
    ];
}
