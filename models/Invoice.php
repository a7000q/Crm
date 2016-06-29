<?php

namespace app\models;

use Yii;
use app\models\Partners;
use app\models\Tranzactions;
use app\models\BankAccounts;
use app\models\Locale;
use yii\helpers\Url;
/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property integer $date
 * @property integer $d1
 * @property integer $d2
 * @property string $sum
 * @property integer $id_partner
 * @property string $litr
 * @property string $price
 * @property string $src
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['d1Text', 'd2Text', 'id_partner', 'id_account'], 'required'],
            [['date', 'd1', 'd2', 'id_partner', 'id_account'], 'integer'],
            [['sum', 'litr', 'price'], 'number'],
            [['src'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'd1' => 'D1',
            'd2' => 'D2',
            'sum' => 'Sum',
            'id_partner' => 'Контрагент',
            'litr' => 'Litr',
            'price' => 'Price',
            'src' => 'Src',
            'd1Text' => 'C',
            'd2Text' => 'По',
            'id_account' => 'Расчетный счет получателя',
            'dateText' => 'Дата',
            'sum' => 'Сумма',
            'partner.name' => 'Контрагент',
            'litr' => 'Литры'
        ];
    }

    public function getD1Text()
    {
        if ($this->d1 != "")
            return date("d.m.Y H:i:s", $this->d1);
        else
            return "";
    }

    public function setD1Text($value)
    {
        $this->d1 = strtotime($value);
    }

    public function getD2Text()
    {
        if ($this->d2 != "")
            return date("d.m.Y H:i:s", $this->d2);
        else
            return "";
    }

    public function setD2Text($value)
    {
        $this->d2 = strtotime($value);
    }

    public function getDateText()
    {
        return date("d.m.Y H:i:s", $this->date);
    }

    public function setDateText($value)
    {
        $this->date = strtotime($value);
    }

    public function getPartners()
    {
        return Partners::arrayAll();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            $this->getData();
     
            return true;
        }
        return false;
    }

    public function getData()
    {
        $this->date = time();

        $tranzactions = Tranzactions::find()->where(["status" => "1"]);
        $tranzactions = $tranzactions->andWhere(['in', 'id_card', $this->getCardsByPartners($this->id_partner)]);
        $tranzactions = $tranzactions->andWhere(['>', 'date', $this->d1]);
        $tranzactions = $tranzactions->andWhere(['<', 'date', $this->d2])->all();

        $price = 0;

        if ($tranzactions)
        {
            foreach ($tranzactions as $tranz) 
            {
                $this->litr += $tranz->doza;
                $price += $tranz->price;
                $this->sum += $tranz->sum;
            }

            $price = $this->sum/$this->litr;
            $this->price = number_format($price, 2);
        }
        else
        {
            $this->price = 0;
            $this->litr = 0;
            $this->sum = 0;
        }

    }

    public function getCardsByPartners($id_partner)
    {
        return Cards::getArrayCardsOnPartner($id_partner);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->createBill();
    }

    private function createBill()
    {

    }

    public function getAccounts()
    {
        return Partners::arrayBankAccountsMyCompany();
    }

    public function getBankAccount()
    {
        return $this->hasOne(BankAccounts::className(), ['id' => 'id_account']);
    }

    public function getMyCompany()
    {
        return $this->bankAccount->partner;
    }

    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'id_partner']);
    }

    public function getDateTextRus()
    {
        $Locale = new Locale();

        return date("d", $this->date)." ".$Locale->getMonth(date("m", $this->date))." ".date("Y", $this->date)." г.";
    }

    public function getNds()
    {
        return number_format(($this->sum - ($this->sum / 1.18)), 2, ",", " ");
    }

    public function getSumPropis()
    {
        $Locale = new Locale();

        return $Locale->num2str($this->sum);
    }
}
