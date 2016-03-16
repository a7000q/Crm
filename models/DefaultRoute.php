<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "default_route".
 *
 * @property integer $id
 * @property integer $id_role
 * @property integer $route
 */
class DefaultRoute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'default_route';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_role', 'route'], 'required'],
            [['id_role', 'route'], 'integer']
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
            'route' => 'Route',
        ];
    }
}
