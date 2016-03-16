<?php

namespace app\models;

use Yii;
use app\models\Sections;

/**
 * This is the model class for table "fuel_delivery_sections".
 *
 * @property integer $id
 * @property integer $id_section
 * @property integer $id_fuel_delivery
 * @property double $kalibr
 * @property double $volume
 * @property double $density
 * @property double $temp
 * @property double $mass
 * @property double $fakt_volume
 * @property double $fakt_mass
 * @property double $diff_mass
 */
class FuelDeliverySections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_delivery_sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_section', 'id_fuel_delivery', 'kalibr', 'volume', 'density', 'temp', 'mass', 'fakt_volume', 'fakt_mass', 'diff_mass', 'name'], 'required'],
            [['id_section', 'id_fuel_delivery', 'name'], 'integer'],
            [['kalibr', 'volume', 'density', 'temp', 'mass', 'fakt_volume', 'fakt_mass', 'diff_mass'], 'number']
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
            'id_fuel_delivery' => 'Id Fuel Delivery',
            'kalibr' => 'Kalibr',
            'volume' => 'Volume',
            'density' => 'Density',
            'temp' => 'Temp',
            'mass' => 'Mass',
            'fakt_volume' => 'Fakt Volume',
            'fakt_mass' => 'Fakt Mass',
            'diff_mass' => 'Diff Mass',
        ];
    }

    public function getSection()
    {
        return $this->hasOne(Sections::className(), ['id' => 'id_section']);
    }
}
