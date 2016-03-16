<?php

namespace app\models;

use Yii;
use app\models\FuelModuleSections;

/**
 * This is the model class for table "terminals".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_fuel_module_section
 */
class Terminals extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'id_fuel_module'], 'required'],
            [['id', 'id_fuel_module'], 'integer'],
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
            'id_fuel_module' => 'Id Fuel Module',
        ];
    }

    public function getFuelModule()
    {
        return $this->hasOne(FuelModule::className(), ['id' => 'id_fuel_module']);
    }
}
