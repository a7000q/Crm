<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tranzactions_history".
 *
 * @property integer $id
 * @property integer $date
 * @property integer $id_tranzaction
 * @property integer $id_partner
 * @property string $sum
 * @property integer $type_tranzaction
 * @property string $partner_name
 * @property string $card_name
 * @property string $litr
 * @property string $price
 * @property string $product_name
 * @property string $card_electro
 */
class TranzactionsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tranzactions_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'id_tranzaction', 'id_partner', 'sum', 'type_tranzaction', 'partner_name', 'card_name', 'litr', 'price', 'product_name', 'card_electro', 
                'id_section'], 'required'],
            [['date', 'id_tranzaction', 'id_partner', 'type_tranzaction', 'id_section'], 'integer'],
            [['sum'], 'number'],
            [['partner_name', 'card_name', 'litr', 'price', 'product_name', 'card_electro', 'price_cost'], 'string', 'max' => 255]
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
            'id_tranzaction' => 'Id Tranzaction',
            'id_partner' => 'Id Partner',
            'sum' => 'Sum',
            'type_tranzaction' => 'Type Tranzaction',
            'partner_name' => 'Partner Name',
            'card_name' => 'Card Name',
            'litr' => 'Litr',
            'price' => 'Price',
            'product_name' => 'Product Name',
            'card_electro' => 'Card Electro',
        ];
    }
}
