<?php

namespace app\modules\api;

/**
 * Api module definition class
 */
class Api extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config/config.php'));
        // custom initialization code goes here
    }
}
