<?php

namespace app\models;

use Yii;
use app\models\BankAccounts;
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
            [['phone'], 'string', 'max' => 100],
            [['director', 'osnovanie'], 'string', 'max' => 255]
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
            'balance' => 'Баланс'
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
}
