<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prices".
 *
 * @property integer $id
 * @property integer $id_product
 * @property integer $id_partner
 * @property string $price
 */
class Prices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_product', 'id_partner', 'price'], 'required'],
            [['id_product', 'id_partner'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_product' => 'Id Product',
            'id_partner' => 'Id Partner',
            'price' => 'Price',
        ];
    }

    static public function getPrice($id_partner, $id_product)
    {
        return static::findOne(['id_product' => $id_product, 'id_partner' => $id_partner]);
    }
}
