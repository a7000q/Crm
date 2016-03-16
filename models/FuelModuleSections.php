<?php

namespace app\models;

use Yii;
use app\models\Products;
use yii\Helpers\ArrayHelper;
use app\models\Prices;
/**
 * This is the model class for table "fuel_module_sections".
 *
 * @property integer $id
 * @property integer $id_module
 * @property integer $name
 * @property double $volume
 * @property double $id_product
 */
class FuelModuleSections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_module_sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_module', 'name', 'volume', 'id_product'], 'required'],
            [['id_module', 'name'], 'integer'],
            [['volume', 'id_product', 'balance', 'balance_fact'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_module' => 'Id Module',
            'name' => 'Название',
            'volume' => 'Обьем',
            'id_product' => 'Продукт',
            'product.name' => 'Продукт',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'id_product']);
    }

    public function getModule()
    {
        return $this->hasOne(FuelModule::className(), ['id' => 'id_module']);
    }

    public function getSensor()
    {
        return $this->hasOne(Sensors::className(), ['id_fuel_module_section' => 'id']);
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

    public function isPrice($id_partner)
    {
        $price = Prices::findOne(['id_product' => $this->id_product, 'id_partner' => $id_partner]);

        if ($price)
            return true;
        else
            return false;
    }

    public function addLitr($volume)
    {
        $this->balance = $this->balance + $volume;
        $this->save();
    }

    public function minusLitr($volume)
    {
        $this->balance = $this->balance - $volume;
        $this->save();
    }

    public function updatePriceLitr($sum_delivery, $volume_delivery)
    {
        $this->last_price = ($sum_delivery + $this->balance * $this->last_price)/($this->balance + $volume_delivery);
        $this->save();
    }

    public function updateDensity($mass, $volume)
    {
        $density = $mass*1000/$volume;
        $this->last_density = $density;
        $this->save();
    }

    public function getNowDensity()
    {
        if ($this->sensor)
        {
            $sensor_monitor = SensorMonitors::find()->where(["id_sensor" => $this->sensor->id])->orderBy(["date" => SORT_DESC])->one();

            if ($sensor_monitor)
            {
                return $sensor_monitor->density;
            }
            else
                return 0;
        }
        else
            return 0;
    }

    public function updatePriceDensityForBay()
    {
        $diff_density = $this->last_density - $this->nowDensity;

        $oneProc = $this->last_density/100;

        $diff_proc = $diff_density * $oneProc;

        $oneProcPrice = $this->last_price/100;

        $this->last_price = $this->last_price + ($diff_proc * $oneProcPrice);

        $this->last_density = $this->nowDensity;

        $this->save();
    }
}
