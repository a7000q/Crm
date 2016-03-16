<?php

namespace app\models;

use Yii;
use app\models\Cards;

/**
 * This is the model class for table "test_card".
 *
 * @property string $NameCompany
 * @property string $NameCustomer
 * @property string $Phone
 * @property integer $Balance
 * @property string $PIN
 * @property integer $id
 * @property string $CardID
 * @property string $fuel
 * @property string $inform
 * @property string $mail
 * @property integer $limit
 * @property string $period
 * @property string $limit_type
 */
class TestCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NameCompany', 'NameCustomer', 'Phone', 'Balance', 'PIN', 'CardID', 'fuel', 'inform', 'mail', 'limit', 'period', 'limit_type'], 'required'],
            [['Balance', 'limit'], 'integer'],
            [['NameCompany', 'NameCustomer', 'inform', 'mail', 'period'], 'string', 'max' => 50],
            [['Phone'], 'string', 'max' => 11],
            [['PIN'], 'string', 'max' => 4],
            [['CardID'], 'string', 'max' => 16],
            [['fuel'], 'string', 'max' => 8],
            [['limit_type'], 'string', 'max' => 10],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NameCompany' => 'Name Company',
            'NameCustomer' => 'Name Customer',
            'Phone' => 'Phone',
            'Balance' => 'Balance',
            'PIN' => 'Pin',
            'id' => 'ID',
            'CardID' => 'Card ID',
            'fuel' => 'Fuel',
            'inform' => 'Inform',
            'mail' => 'Mail',
            'limit' => 'Limit',
            'period' => 'Period',
            'limit_type' => 'Limit Type',
        ];
    }

    public function getCardHex()
    {
        $number = substr($this->CardID, 1);

        $arr = explode(".", $number);

        foreach ($arr as $val) 
        {
            $res[] = $this->getHex($val);
        }

        return implode("-", $res);
    }

    public function getHex($val)
    {
        $table = "0123456789ABCDEF";

        $x = $val;


        $h = (int) ($x / 16);
        $l = $x - ($h * 16);


        return $table[$h].$table[$l];
    }

    public function pull()
    {
        $id_txt = $this->inform;
        $id_electro = $this->cardHex;
        $id_partner = $this->getPartner();
        $name = $this->NameCustomer;

        if ($id_partner != false)
        {
            $card = new Cards();
            $card->id_txt = $id_txt;
            $card->id_electro = $id_electro;
            $card->id_partner = $id_partner;
            $card->name = $name;
            if ($card->validate())
                $card->save();

            $this->delete();
        }
    }

    private function getPartner()
    {
        switch ($this->NameCompany)
        {
            case "ИП Гараев":
                $res = 2;
                break;
            case "ИП Зыякова":
                $res = 2;
                break;
            case "ИП Сахапов":
                $res = 2;
                break;
            case "ИП Фазуллин":
                $res = 2;
                break;
            case "ИП Ялаков":
                $res = 2;
                break;
            case "Ликада+":
                $res = 2;
                break;
            case "ЛТС":
                $res = 7;
                break;
            case 'ООО "Инкотрэк"':
                $res = 3;
                break;
            case 'ООО "Компания Магеллан"':
                $res = 5;
                break;
            case 'ООО Инкотрэк':
                $res = 3;
                break;
            case "СпецДорСтрой":
                $res = 4;
                break;
            default:
                $res = false;
                break;
        }

        return $res;

        
    }
}
