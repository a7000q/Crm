<?php 
namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;

class AddFuelDeliveryForm extends Model
{
	public $id_trailer = 0;
    public $id_fuel_module = 0;
    public $id_fuel_module_section = 0;
    public $id_product;

    public $volume = [];
    public $density = [];
    public $temp = [];
    public $mass = [];
    public $pipe = [];

    public $remove_section = [];
    public $driver;

    public $date;

    public $activeSections;

    public $FuelDelivery;

	public function rules()
    {
        return [
            [['id_trailer', 'id_fuel_module', 'id_fuel_module_section', 'id_product', 'driver', 'volume', 'temp', 'mass', 'density'], 'required'],
            [['volume', 'temp', 'remove_section', 'pipe'], 'each', 'rule' => ['integer']],
            [['temp'], 'each', 'rule' => ['integer', 'max' => 60, 'min' => -40]],
            [['density'], 'each', 'rule' => ['double', 'max' => 0.9, 'min' => 0.7]],
            [['mass'], 'each', 'rule' => ['double']],
            [['id_product', 'date'], 'integer'],
            ['driver', 'string'],
            [['mass'], 'each', 'rule' => ['compare', 'compareValue' => 0, 'operator' => '>']]
        ];
    }


    public function attributeLabels()
    {
        return [
            'id_trailer' => 'Прицеп',
            'id_fuel_module' => 'Топливный модуль',
            'id_fuel_module_section' => 'Секция топливного модуля',
            'volume' => 'Объем',
            'density' => 'Плотность', 
            'temp' => 'Температура', 
            'mass' => 'Масса',
            'id_product' => 'Продукт',
            'pipe' => 'Учитывать обьем трубы'
        ];
    }

	public function getTrailers()
	{
		$trailers = Trailers::find()->all();
		$trailers = ArrayHelper::map($trailers, 'id', 'gos_number');
		

		return $trailers;
	}
    

    public function getSections()
    {
        if ($this->id_trailer != 0)
        {
            $sections = Sections::find()->where(['id_trailer' => $this->id_trailer])->all();

            foreach($sections as $k => $section)
            {
                if (in_array($section->id, $this->remove_section))
                {
                    unset($sections[$k]);
                }
            }

            return $sections;
        }
        else
            return false;
    }



    public function getFuelModules()
    {
        $modules = FuelModule::find()->all();
        $modules = ArrayHelper::map($modules, 'id', 'name');

        return $modules;
    }

    public function getFuelModuleSections()
    {
        if ($this->id_fuel_module != 0)
        {
            $module_sections = FuelModuleSections::find()->where(['id_module' => $this->id_fuel_module])->all();

            if (count($module_sections) > 1)
                $module_sections = ArrayHelper::map($module_sections, 'id', 'name');
            else
                $module_sections = $module_sections[0]->id;
            

            return $module_sections;
        }
        else
            return false;
    }

    public function createDelivery()
    {
        if ($this->FuelDelivery->validate())
        {
            $this->FuelDelivery->save();
            $id_fuel_delivery = $this->FuelDelivery->id;

            foreach ($this->activeSections as $section)
            {
                $section->id_fuel_delivery = $id_fuel_delivery;

                $driver = new Drivers();
                $driver->addDriver($this->driver);

                if ($section->validate())
                    $section->save();
            }

            return true;
        }
        
        return false;
    } 

    public function getParField($array, $id)
    {
        if (isset($array[$id]))
            return $array[$id];
        else
            return 0;
    }

    public function getActiveSection($id)
    {
        if (isset($this->volume[$id]))
            return true;

        if (isset($this->density[$id]))
            return true;

        if (isset($this->temp[$id]))
            return true;

        if (isset($this->mass[$id]))
            return true;

        return false;
    }

    public function getProducts()
    {
        $products = Products::find()->rightJoin(ProductPassports::tableName(), "product_passports.id_product = products.id")->groupBy("products.id")->all();
        $products = ArrayHelper::map($products, 'id', 'name');
        

        return $products;
    }

    public function getProductPassport()
    {
        $passport = ProductPassports::find()->where(['id_product' => $this->id_product])->orderBy('date DESC')->one();
        return $passport;
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

    public function formData()
    {
        $this->date = time();
        $user = Yii::$app->user->identity;

        $this->FuelDelivery = new FuelDelivery();
        $this->FuelDelivery->scenario = "step1";
        $this->FuelDelivery->id_user = $user->id;
        $this->FuelDelivery->date = $this->date;
        $this->FuelDelivery->id_fuel_module = $this->id_fuel_module;
        $this->FuelDelivery->id_fuel_module_section = $this->id_fuel_module_section;
        $this->FuelDelivery->driver = $this->driver;
        $this->FuelDelivery->id_product_passport = $this->productPassport->id;
        $this->FuelDelivery->id_trailer = $this->id_trailer;
        $this->FuelDelivery->gos_number = $this->trailer->gos_number;
        $this->FuelDelivery->kalibr = 0;
        $this->FuelDelivery->volume = 0;
        $this->FuelDelivery->fakt_volume = 0;
        $this->FuelDelivery->mass = 0;
        $this->FuelDelivery->fakt_mass = 0;
        $this->FuelDelivery->diff_mass = 0;

        foreach ($this->sections as $section)
        {
            if ($this->getActiveSection($section->id))
            {   
                $r = new FuelDeliverySections();
                $r->volume = $this->getParField($this->volume, $section->id);
                $r->density = $this->getParField($this->density, $section->id);
                $r->temp = $this->getParField($this->temp, $section->id);
                $r->mass = $this->getParField($this->mass, $section->id);
                $r->kalibr = $section->volume;

                if ($this->getParField($this->pipe, $section->id) == 1)
                    $r->kalibr += $section->volume_pipe;

                $r->fakt_volume = $r->kalibr - $r->volume;
                $r->fakt_mass = ($r->fakt_volume * $r->density)/1000;
                $r->diff_mass = ($r->fakt_mass - $r->mass)*1000;
                $r->id_section = $section->id;
                $r->name = $section->name;


                $this->activeSections[] = $r;
                $this->FuelDelivery->kalibr += $r->kalibr;
                $this->FuelDelivery->volume += $r->volume;
                $this->FuelDelivery->fakt_volume += $r->fakt_volume;
                $this->FuelDelivery->mass += $r->mass;
                $this->FuelDelivery->fakt_mass += $r->fakt_mass;
                $this->FuelDelivery->diff_mass += $r->diff_mass;
            }
        }
    }

    public function getAddress()
    {
        $module = FuelModule::findOne($this->id_fuel_module);
        return $module->address;
    }

    public function getTrailer()
    {
        $trailer = Trailers::findOne($this->id_trailer);
        return $trailer;
    }

    public function getProduct()
    {
        $product = Products::findOne($this->id_product);
        return $product;
    }

    public function getUserName()
    {
        $user = Yii::$app->user->identity;
        return $user->surname." ".$user->name;
    }
}