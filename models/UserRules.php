<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_rules".
 *
 * @property integer $id
 * @property integer $id_role
 * @property string $controller
 * @property string $action
 */
class UserRules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_rules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_role', 'controller', 'action'], 'required'],
            [['id_role'], 'integer'],
            [['controller', 'action'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_role' => 'Id Role',
            'controller' => 'Controller',
            'action' => 'Action',
        ];
    }
}
