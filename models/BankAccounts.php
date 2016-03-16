<?php

namespace app\models;

use Yii;
use app\models\Partners;
/**
 * This is the model class for table "bank_accounts".
 *
 * @property integer $id
 * @property integer $id_partner
 * @property string $bank_name
 * @property string $checking_account
 * @property string $corresponding_account
 * @property string $bik
 */
class BankAccounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank_accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_partner', 'bank_name', 'checking_account', 'corresponding_account', 'bik'], 'required'],
            [['id_partner'], 'integer'],
            [['bank_name'], 'string', 'max' => 1000],
            [['checking_account', 'corresponding_account'], 'string', 'max' => 30],
            [['bik'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_partner' => 'Id Partner',
            'bank_name' => 'Наименование банка',
            'checking_account' => 'Рассчетный счет',
            'corresponding_account' => 'Корреспондирующий счет',
            'bik' => 'БИК',
        ];
    }

    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'id_partner']);
    }
}
