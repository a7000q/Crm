<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\Helpers\ArrayHelper;

/**
 * This is the model class for table "fuel_delivery".
 *
 * @property integer $id
 * @property integer $id_section
 * @property double $volume
 * @property double $density
 * @property double $temp
 * @property double $mass
 * @property integer $id_user
 * @property integer $date
 * @property integer $id_fuel_module_section
 */
class FuelDelivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_delivery';
    }

    public $typePrice = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'date', 'id_fuel_module_section', 'driver', 'id_product_passport', 'id_trailer', 'gos_number',
                'kalibr', 'volume', 'fakt_volume', 'mass', 'fakt_mass', 'diff_mass', 'id_fuel_module'], 'required', 'on' => 'step1'],
            [['id_user', 'date', 'id_fuel_module_section', 'id_trailer', 'id_product_passport', 'id_fuel_module', 'date'], 'integer'],
            [['volume', 'kalibr', 'mass', 'fakt_volume', 'fakt_mass', 'diff_mass', 'priceLitr'], 'number'],
            [['driver', 'gos_number'], 'string'],
            [['price', 'price_track', 'id_partner', 'typePrice', 'id_partner_track'], 'required', 'on' => 'step2'],
            [['id_fuel_module', 'driver', 'id_product'], 'required', 'on' => 'update'],
            ['dateText', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'date' => 'Date',
            'id_fuel_module_section' => 'Id Fuel Module Section',
            'dateText' => 'Дата',
            'fuelModule.name' => 'Топливный модуль',
            'fuelModuleSection.name' => 'Секция топливного модуля',
            'gos_number' => 'Гос. номер', 
            'fakt_volume' => 'Кол. л. факт',
            'fakt_mass' => 'Кол. т. факт',
            'kalibr' => 'Калибровка',
            'volume' => 'Долив',
            'driver' => 'Водитель',
            'user.full_name' => 'Приемщик',
            'mass' => 'Кол. т. накладная',
            'diff_mass' => 'Разница',
            'product.name' => 'Продукт',
            'id_fuel_module' => 'Топливный модуль',
            'id_product' => 'Продукт',
            'id_trailer' => 'Прицеп',
            'id_fuel_module_section' => 'Секция топливного модуля',
            'id_partner' => 'Поставщик',
            'id_partner_track' => 'Перевозщик',
            'price' => 'Цена за тонну',
            'price_track' => 'Цена перевозки(за тонну)'
        ];
    }

    public function scenarios()
    {
        return [
            'step1' => ['id_user', 'date', 'id_fuel_module_section', 'driver', 'id_product_passport', 'id_trailer', 'gos_number',
                'kalibr', 'volume', 'fakt_volume', 'mass', 'fakt_mass', 'diff_mass', 'id_fuel_module'],
            'step2' => ['price', 'price_track', 'id_partner', 'typePrice', 'priceLitr', 'id_partner_track', 'date', 'dateText'],
            'update' => ['id_fuel_module', 'driver', 'id_product', 'id_trailer', 'id_fuel_module_section'],
            'correct' => ['date', 'price', 'price_track', 'priceLitr'],
            'crud-update' => ['dateText', 'kalibr', 'volume', 'mass', 'id_partner', 'id_partner_track', 'price', 'price_track']
        ];
    }

    public function getFuelDeliverySections()
    {
        return $this->hasMany(FuelDeliverySections::className(), ['id_fuel_delivery' => 'id']);
    }


    public function getDateText()
    {
        return date("d.m.Y H:i", $this->date);
    }

    public function setDateText($value)
    {
        $this->date = strtotime($value);
    }

    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'id_partner']);
    }

    public function getFuelModule()
    {
        return $this->hasOne(FuelModule::className(), ['id' => 'id_fuel_module']);
    }

    public function getFuelModuleSection()
    {
        return $this->hasOne(FuelModuleSections::className(), ['id' => 'id_fuel_module_section']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    public function getProductPassport()
    {
        return $this->hasOne(ProductPassports::className(), ['id' => 'id_product_passport']);
    }

    public function getProduct()
    {
        if ($this->productPassport)
            return $this->productPassport->product;
        else
            return false;
    }

    public function getPartners()
    {
        $partners = Partners::find()->all();

        $partners = ArrayHelper::map($partners, 'id', 'name');
        return $partners;
    }

    public function correctPrice()
    {
        if ($this->typePrice == 2)
            $this->price_track = $this->price_track/$this->fakt_mass;

        if ($this->mass != 0)
            $this->priceLitr = (($this->price + $this->price_track)*$this->mass)/$this->fakt_volume;
        else
            $this->priceLitr = (($this->price + $this->price_track)*$this->fakt_mass)/$this->fakt_volume;
    }

    public function addFuelBalance()
    {
        $moduleSection = $this->fuelModuleSection;
        $moduleSection->updatePriceLitr($this->priceLitr*$this->fakt_volume, $this->fakt_volume);
        $moduleSection->updateDensity($this->fakt_mass, $this->fakt_volume);
        $moduleSection->addLitr($this->fakt_volume);
    }

    public function getId_product()
    {
        return $this->product->id;
    }

    public function setId_product($value)
    {
        $this->id_product_passport = $this->productPassportOnProduct($value)->id;
    }

    public function productPassportOnProduct($value)
    {
        $passport = ProductPassports::find()->where(['id_product' => $value])->orderBy('date DESC')->one();
        return $passport;
    }

    public function getProducts()
    {
        $products = Products::find()->all();
        $products = ArrayHelper::map($products, 'id', 'name');
        

        return $products;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
     
            if (!$this->validLine())
                return false;

            $this->correctData();
     
            return true;
        }
        return false;
    }

    public function correctData()
    {
        $this->fakt_volume = $this->kalibr - $this->volume;
        
        $sum = ($this->price + $this->price_track)*$this->mass;
        $this->priceLitr = $sum/$this->fakt_volume;
    }

    public function setPriceOnFuelModule()
    {
        $sumOstatokMoney = $this->fuelModuleSection->balance * $this->fuelModuleSection->last_price;
        $sumFaktMoney = $this->priceLitr*$this->fakt_volume;
        $sum = $sumFaktMoney + $sumOstatokMoney;

        $sumVolume = $this->fuelModuleSection->balance + $this->fakt_volume;

        $price = $sum/$sumVolume;

        $this->fuelModuleSection->last_price = $price;
        $this->fuelModuleSection->save();
    }

    private function validLine()
    {
        $fuel_delivery = FuelDelivery::find()->where(['id_user' => $this->id_user, 'id_fuel_module' => $this->id_fuel_module, 'id_fuel_module_section' => $this->id_fuel_module_section,
            'driver' => $this->driver, 'id_product_passport' => $this->id_product_passport, 'id_trailer' => $this->id_trailer, 'gos_number' => $this->gos_number,
            'kalibr' => $this->kalibr, 'volume' => $this->volume, 'fakt_volume' => $this->fakt_volume, 'mass' => $this->mass, 'fakt_mass' => $this->fakt_mass, 
            'diff_mass' => $this->diff_mass])->one();

        if ($fuel_delivery)
        {
            $date_now = date("d.m.Y", $this->date);
            $date_rec = date("d.m.Y", $fuel_delivery->date);

            if ($date_now == $date_rec)
                return false;
        }

        return true;
    }

    public function getFuelModules()
    {
        $modules = FuelModule::find()->all();
        $modules = ArrayHelper::map($modules, 'id', 'name');

        return $modules;
    }

    public function getDrivers()
    {
        $drivers = Drivers::find()->all();
        
        $result = array("");
        if ($drivers)
            foreach($drivers as $driver)
                $result[] = $driver->name;
        
        return $result;
    }

    public function getTrailers()
    {
        $trailers = Trailers::find()->all();
        $trailers = ArrayHelper::map($trailers, 'id', 'gos_number');
        

        return $trailers;
    }

    public function getFuelModuleSections()
    {
        if ($this->id_fuel_module != 0)
        {
            $module_sections = FuelModuleSections::find()->where(['id_module' => $this->id_fuel_module])->all();

            
            $module_sections = ArrayHelper::map($module_sections, 'id', 'name');
           

            return $module_sections;
        }
        else
            return false;
    }

    public function getSummDelivery()
    {
        if ($this->mass != 0)
            $result = ($this->price + $this->price_track)*$this->mass;
        else
            $result = ($this->price + $this->price_track)*$this->fakt_mass;

        return $result;
    }

    public function getNextDateOnSection()
    {
        $delivery = FuelDelivery::find()->where(['id_fuel_module_section' => $this->id_fuel_module_section])->andWhere(['>', 'date', $this->date])->orderBy(['date' => SORT_ASC])->one();

        if ($delivery)
            return $delivery->date;
        else
            return time();
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) 
        {
            if ($this->fuelDeliverySections)
                foreach ($this->fuelDeliverySections as $section) 
                    $section->delete();

            return true;
        } else {
            return false;
        }
    }

}
