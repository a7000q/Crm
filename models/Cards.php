<?php

namespace app\models;

use Yii;
use app\models\Prices;
use app\models\Partners;
/**
 * This is the model class for table "cards".
 *
 * @property integer $id
 * @property integer $id_txt
 * @property string $id_electro
 * @property integer $id_partner
 * @property integer $id_type_limit
 * @property integer $value_limit
 */
class Cards extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cards';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_txt', 'id_electro', 'id_partner'], 'required'],
            [['id_txt', 'id_partner', 'id_type_limit', 'value_limit'], 'integer'],
            [['id_electro'], 'string', 'max' => 100],
            [['name'], 'string'],
            ['id_electro', "unique"]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_txt' => 'Id Txt',
            'id_electro' => 'Id Electro',
            'id_partner' => 'Id Partner',
            'id_type_limit' => 'Id Type Limit',
            'value_limit' => 'Value Limit',
        ];
    }

    public function isPermissionFuelDoza($id_product, $doza)
    {
        $price = Prices::getPrice($this->id_partner, $id_product);

        $sum_price = $doza*$price->price;

        if ($this->partner->balance < $sum_price)
            return false;

        return true;

    }

    public function getSumDoza($id_product, $doza, $id_section)
    {
        $price = Prices::getPrice($this->id_partner, $id_product);

        if ($price->id_type == 1)
            $sum_price = $doza*$price->price;
        else if ($price->id_type == 2)
        {
            $section = FuelModuleSections::findOne($id_section);
            $sum_price = ($section->last_price + $price->price) * $doza;
        }

        return $sum_price;
    }

    public function pullMoney($id_product, $doza, $id_section)
    {
        $sum_price = $this->getSumDoza($id_product, $doza, $id_section);

        $this->partner->minusMoney($sum_price);

        return $sum_price;
    }

    public function backMoney($sum)
    {
        $this->partner->plusMoney($sum);
    }


    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'id_partner']);
    }
}
