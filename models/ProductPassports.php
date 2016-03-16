<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\base\Model;
/**
 * This is the model class for table "product_passports".
 *
 * @property integer $id
 * @property integer $id_product
 * @property string $name
 * @property string $src
 * @property integer $date
 */
class ProductPassports extends \yii\db\ActiveRecord
{
    
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_passports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file', 'id_product'], 'required'],
            [['id_product', 'date'], 'integer'],
            [['name', 'src'], 'string', 'max' => 1000],
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
            'id_product' => 'Id Product',
            'name' => 'Имя',
            'src' => 'Файл',
            'dateText' => 'Дата загрузки',
            'file' => 'Файл'
        ];
    }

    public function removeDublicate()
    {
        $passport = static::findOne(['id_product' => $this->id_product, 'name' => $this->name]);
        
        if ($passport)
            $passport->delete(); 
    }

    public function upload($id_product)
    {   
        $this->id_product = $id_product;
        if ($this->validate()) {
            $this->date = time();
            $this->name = $this->product->name."_".date("d-m-Y");
            $file_name =  $this->name.'.'.$this->file->extension;

            if (!is_dir('uploads/product-passports/'.$this->id_product.'/'))
                mkdir('uploads/product-passports/'.$this->id_product.'/', 0777, true);

            $this->src = 'uploads/product-passports/'.$this->id_product."/".$file_name;
            $this->file->saveAs($this->src);

            $this->removeDublicate();
            $this->save();
            return true;
        } else {
            return false;
        }
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'id_product']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            unlink($this->src);
            return true;
        } else {
            return false;
        }
    } 

    public function getDateText()
    {
        return date("d.m.Y", $this->date);
    }   

}
