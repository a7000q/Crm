<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fuel_module_sections".
 *
 * @property integer $id
 * @property integer $id_module
 * @property integer $name
 * @property double $volume
 */
class FuelModuleSections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_module_sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_module', 'name', 'volume'], 'required'],
            [['id_module', 'name'], 'integer'],
            [['volume'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_module' => 'Id Module',
            'name' => 'Name',
            'volume' => 'Volume',
        ];
    }
}
