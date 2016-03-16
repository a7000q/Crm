<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $name
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['short_name'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'short_name' => 'Краткое название'
        ];
    }

    public function getProductPassports()
    {
        return $this->hasMany(ProductPassports::className(), ['id_product' => 'id']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) 
        {
            
            if ($this->productPassports)
                foreach($this->productPassports as $passport)
                    $passport->delete();

            $dir = 'uploads/product-passports/'.$this->id;
            if (is_dir($dir))
                rmdir($dir);

            return true;
        } 
        else 
        {
            return false;
        }
    } 
}
