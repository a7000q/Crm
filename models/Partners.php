<?php

namespace app\models;

use Yii;
use app\models\BankAccounts;
use app\models\Inpayment;
use app\models\Tranzactions;
use app\models\Cards;
use yii\Helpers\ArrayHelper;
/**
 * This is the model class for table "partners".
 *
 * @property integer $id
 * @property string $inn
 * @property string $full_name
 * @property string $address
 * @property string $fakt_address
 * @property string $pravo_forma
 * @property string $name
 * @property string $kpp
 * @property string $ogrn
 * @property string $okved
 * @property string $okato
 * @property string $oktmo
 * @property string $okogu
 * @property string $okfs
 * @property string $okopf
 * @property string $okpo
 * @property string $email
 * @property string $phone
 * @property string $director
 * @property string $osnovanie
 */
class Partners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inn', 'full_name', 'address', 'fakt_address', 'pravo_forma', 'name', 'kpp', 'ogrn', 'email', 'phone', 'director', 'osnovanie'], 'required'],
            [['inn', 'kpp', 'ogrn', 'okato', 'oktmo', 'okogu', 'okfs', 'okopf', 'okpo'], 'string', 'max' => 20],
            [['full_name', 'address', 'fakt_address', 'name', 'okved', 'email'], 'string', 'max' => 1000],
            [['pravo_forma'], 'string', 'max' => 10],
            [['phone', 'phoneSms'], 'string', 'max' => 100],
            [['director', 'osnovanie'], 'string', 'max' => 255],
            [['balance', 'limit'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inn' => 'ИНН',
            'full_name' => 'Полное наименование',
            'address' => 'Юридический адрес',
            'fakt_address' => 'Фактический адрес',
            'pravo_forma' => 'Правовая форма',
            'name' => 'Краткое наименование',
            'kpp' => 'КПП',
            'ogrn' => 'ОГРН',
            'okved' => 'ОКВЭД',
            'okato' => 'ОКАТО',
            'oktmo' => 'ОКТМО',
            'okogu' => 'ОКОГУ',
            'okfs' => 'ОКФС',
            'okopf' => 'ОКОПФ',
            'okpo' => 'ОКПО',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'director' => 'Директор',
            'osnovanie' => 'Действует на основании',
            'balance' => 'Баланс',
            'phoneSms' => 'Телефон для SMS',
            'limit' => 'Лимит'
        ];
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) 
        {
            
            if ($this->bankAccounts)
                foreach($this->bankAccounts as $accounts)
                    $accounts->delete();

            return true;
        } 
        else 
        {
            return false;
        }
    } 

    public function getBankAccounts()
    {
        return $this->hasMany(BankAccounts::className(), ['id_partner' => 'id']);
    }

    public function minusMoney($sum)
    {
        $this->balance = $this->balance - $sum;

        $this->save();
    }

    public function plusMoney($sum)
    {
        $this->balance = $this->balance + $sum;

        $this->save();
    }

    public function getSumInpayments()
    {
        return Inpayment::find()->where(['id_partner' => $this->id])->sum('sum');
    }

    public function getSumTranzactions()
    {
        $cards = $this->cardsArray();

        $tranz = Tranzactions::find()->where(['in', 'id_card', $cards])->all();
        //print_r($tranz);

        $sum = 0;
        foreach ($tranz as $t)
        {
            $sum += $t->sum;
        }

        return $sum;
    }

    public function getCards()
    {
       return $this->hasMany(Cards::className(), ['id_partner' => 'id']);
    }

    public function cardsArray()
    {
        $r = "";
        foreach ($this->cards as $card)
            $r[] = $card->id;

        return $r;
    }

    public function calcBalance()
    {
        $this->balance = $this->sumInpayments - $this->sumTranzactions;
        $this->save();
    }

    static public function arrayAll()
    {
        $array = self::find()->all();
        $array = ArrayHelper::map($array, 'id', 'name');

        return $array;
    }

    static public function arrayBankAccountsMyCompany()
    {
        $company = self::find()->where(['my' => 1])->one();

        $accounts = BankAccounts::find()->where(['id_partner' => $company->id])->all();
        $array = ArrayHelper::map($accounts, 'id', 'bank_name');

        return $array;
    }

}
