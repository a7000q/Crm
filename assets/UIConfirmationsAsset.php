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
class UIConfirmationsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      
    ];
    public $js = [
    	'../assets2/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js',
    	'../assets2/pages/scripts/ui-confirmations.min.js'
    ];
    public $depends = [
       
    ];
}
