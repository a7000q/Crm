<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\FuelModuleSections;
/**
 * This is the model class for table "fuel_module".
 *
 * @property integer $id
 * @property string $name
 */
class FuelModule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['name'], 'string', 'max' => 1000],
            [['address'], 'string', 'max' => 2000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'address' => 'Адрес'
        ];
    }

    

   public function getFuelModuleSections()
    {
        return $this->hasMany(FuelModuleSections::className(), ['id_module' => 'id']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) 
        {
            
            if ($this->fuelModuleSections)
                foreach($this->fuelModuleSections as $section)
                    $section->delete();

            return true;
        } 
        else 
        {
            return false;
        }
    } 
}
