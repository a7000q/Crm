<?php

namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use app\models\Prices;
use app\models\Partners;
use app\models\TypeLimit;
use app\models\TypeMeasurement;
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
    
    public $cardNumber = false;
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
            [['id_txt', 'id_partner', 'id_type_limit', 'value_limit', 'id_type_measurement_limit'], 'integer'],
            [['id_electro'], 'string', 'max' => 100],
            [['name'], 'string'],
            ['id_electro', "unique"],
            ['cardNumber', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_txt' => 'Номер карты',
            'id_electro' => 'Электронный номер',
            'id_partner' => 'Контрагент',
            'id_type_limit' => 'Тип лимита',
            'value_limit' => 'Значение лимита',
            'name' => 'Название',
            'id_type_measurement_limit' => 'Единица измерения лимита',
            'typeLimit.name' => 'Тип лимита',
            'cardNumber' => 'Цифровой номер карты'
        ];
    }

    public function isPermissionFuelDoza($id_product, $doza)
    {
        $price = Prices::getPrice($this->id_partner, $id_product);

        $sum_price = $doza*$price->price;

        if ((($this->partner->balance - $sum_price) + $this->partner->limit) < 0)
            return false;

        return $this->getLimitValid($doza, $sum_price);
    }

    public function getLimitValid($doza, $sum)
    {
        $res = true;

        if ($this->typeLimit)
        {
            $period = $this->typeLimit->period;

            $tranzation = Tranzactions::find()->where(['status' => 1, 'id_card' => $this->id])->andWhere(['>=', 'date', $period["start"]])->andWhere(['>=', 'date', $period["end"]]);

            switch ($this->id_type_measurement_limit) 
            {
                case 1:
                    $res = $this->getLitrValid($tranzation, $doza);
                    break;

                case 2:
                    $res = $this->getRublValid($tranzation, $doza, $sum);
                    break;

                default:
                    $res = $this->getLitrValid($tranzation, $doza);
                    break;
            }


        }

        return $res;
    }

    private function getLitrValid($tranzactions, $doza)
    {
        $litrs = $tranzactions->sum('doza');

        $sum_litr = $litrs + $doza;

        if ($this->value_limit >= $sum_litr)
            return true;
        else if ($litrs < $this->value_limit)
        {
            $dz = $this->value_limit - $litrs;

            return $dz;
        }
        else
            return false;
    }

    private function getRublValid($tranzactions, $doza, $sum_doza)
    {
        $summ = 0;
        foreach ($tranzactions as $tranz) 
        {
            $summ += $tranz->sum;
        }

        $summ_doza = $summ + $sum_doza;

        if ($this->value_limit >= $summ_doza)
            return true;
        else if ($this->value_limit > $summ)
        {
            $sm = $this->value_limit - $summ;

            $price = $summ_doza/$doza;

            if ($price != 0)
                $dz = (int) $sm/$price;
            else
                return false;
        }
        return false;

    }

    public function getTypeLimit()
    {
        return $this->hasOne(TypeLimit::className(), ['id' => 'id_type_limit']);
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

    static public function getArrayCardsOnPartner($id_partner)
    {
        $cards = self::find()->where(['id_partner' => $id_partner])->all();
        foreach ($cards as $card)
            $result[] = $card->id;
    

        return $result;
    }

    public function getTypeLimitsArray()
    {
        $types = TypeLimit::find()->all();

        $arr0[0] = "Лимит не установлен";
        $arr = ArrayHelper::map($types, 'id', 'name');

        $result = ArrayHelper::merge($arr0, $arr);

        return $result;
    }

    public function getTypeMeasurementsArray()
    {
        $types = TypeMeasurement::find()->all();

        $result = ArrayHelper::map($types, "id", "name");
        return $result;
    }

    public function getPartners()
    {
        $partners = Partners::find()->all();
        $partners = ArrayHelper::map($partners, 'id', 'name');

        return $partners;
    }

    public function convertAndSave()
    {
        if ($this->cardNumber)
        {
            $electro = base_convert($this->cardNumber, 10, 16);

            $id_electro = "00-";

            $number = $electro; 
            $array = array(); 

            while ($number > 0) 
            { 
                $array[] = $number % 10; 
                $number = intval($number / 10);  
            } 

            $array = array_reverse($array); 

            $i = 0;

            while (count($array) > $i) 
            {
                $id_electro .= $array[$i];

                if (isset($array[$i + 1]))
                    $id_electro .= $array[$i + 1];

                $id_electro .= "-";

                $i += 2;
            }

            $id_electro = substr($id_electro, 0, -1);

            $this->id_electro = $id_electro;

            if ($this->validate())
            {
                $this->save();

                return true;
            }
        }

        return false;
    }
}
