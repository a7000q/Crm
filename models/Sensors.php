<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sensors".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_fuel_module_section
 */
class Sensors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sensors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id_fuel_module_section'], 'required'],
            [['id_fuel_module_section'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'id_fuel_module_section' => 'Id Fuel Module Section',
        ];
    }

    public function getFuelModuleSection()
    {
        return $this->hasOne(FuelModuleSections::className(), ['id' => 'id_fuel_module_section']);
    }
}
