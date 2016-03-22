<?php

namespace app\models;

use Yii;
use app\models\Products;
use app\models\Partners;
use yii\Helpers\ArrayHelper;
use app\models\PriceType;
use yii\web\UploadedFile;
use app\models\PricesHistory;
use yii\helpers\Html;

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
    public $file;
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
            [['id_product', 'id_partner', 'price', 'id_type'], 'required'],
            [['id_product', 'id_partner', 'id_type'], 'integer'],
            [['price'], 'number'],
            [['file_src'], 'string'],
            [['id_partner', 'id_product'], 'unique', 'targetAttribute' => ['id_partner', 'id_product']],
            ['file', 'file']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_product' => 'Продукт',
            'id_partner' => 'Id Partner',
            'price' => 'Цена',
            'product.short_name' => 'Продукт',
            'id_type' => 'Тип цены',
            'type.name' => 'Тип цены',
            'file' => 'Файл',
            'fileSrc' => 'Доп.соглашение'
        ];
    }

    static public function getPrice($id_partner, $id_product)
    {
        return static::findOne(['id_product' => $id_product, 'id_partner' => $id_partner]);
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ["id" => "id_product"]);
    }

    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ["id" => "id_partner"]);
    }

    public function getProducts()
    {
        $products = Products::find()->all();

        if ($products)
            $products = ArrayHelper::map($products, 'id', 'name');
        else
            $products = false;

        return $products;
    }

    public function getType()
    {
        return $this->hasOne(PriceType::className(), ["id" => "id_type"]);
    }

    public function getTypes()
    {
        $types = PriceType::find()->all();

        $types = ArrayHelper::map($types, "id", "name");

        return $types;
    }


    public function addFile()
    {
        $file_src = "";
        $date = time();
        if ($this->file != false)
        {
            if (!is_dir('uploads/price/'.$this->id.'/'))
                mkdir('uploads/price/'.$this->id.'/', 0777, true);

            $file_src = "uploads/price/".$this->id."/".$date."_".$this->file->baseName.".".$this->file->extension;

            $this->file->saveAs($file_src);

            $this->file_src = $file_src;
            $this->validate();
            $this->save();

        }


     
    }

    public function getFileSrc()
    {
        if ($this->file_src != "")
            return Html::a('Открыть', $this->file_src, ["target" => "_blank"]);
        else
            return "";
    }

    public function getPriceP($id_section)
    {
        if ($this->id_type == 1)
            return $this->price;
        else if ($this->id_type == 2)
        {
            $section = FuelModuleSections::findOne($id_section);
            $price = $section->last_price + $this->price;

            return $price;
        }
    }

}
