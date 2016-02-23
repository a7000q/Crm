<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fuel_delivery".
 *
 * @property integer $id
 * @property integer $id_section
 * @property double $volume
 * @property double $density
 * @property double $temp
 * @property double $mass
 * @property integer $id_user
 * @property integer $date
 * @property integer $id_fuel_module_section
 */
class FuelDelivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_section', 'volume', 'density', 'temp', 'mass', 'id_user', 'date', 'id_fuel_module_section'], 'required'],
            [['id_section', 'id_user', 'date', 'id_fuel_module_section'], 'integer'],
            [['volume', 'density', 'temp', 'mass'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_section' => 'Id Section',
            'volume' => 'Volume',
            'density' => 'Density',
            'temp' => 'Temp',
            'mass' => 'Mass',
            'id_user' => 'Id User',
            'date' => 'Date',
            'id_fuel_module_section' => 'Id Fuel Module Section',
        ];
    }
}
