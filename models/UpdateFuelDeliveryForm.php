<?php 
namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;

class UpdateFuelDeliveryForm extends Model
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
    public $trailer_sections = [];

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
            
            $module_sections = ArrayHelper::map($module_sections, 'id', 'name');
            
            return $module_sections;
        }
        else
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

    public function getSection($id)
    {
        return Sections::findOne($id);
    }

    public function formData()
    {
        foreach ($this->activeSections as $k => $section) 
        {
           if (in_array($section->id, $this->remove_section))
                unset($this->activeSections[$k]);
            else if (isset($this->activeSections[$section->id]))
            {
                $this->activeSections[$section->id]->volume = $this->getParField($this->volume, $section->id);
                $this->activeSections[$section->id]->density = $this->getParField($this->density, $section->id);
                $this->activeSections[$section->id]->temp = $this->getParField($this->temp, $section->id);
                $this->activeSections[$section->id]->mass = $this->getParField($this->mass, $section->id);
                $this->activeSections[$section->id]->kalibr = $section->kalibr;

                if ($this->getParField($this->pipe, $section->id) == 1)
                    $this->activeSections[$section->id]->kalibr += $section->volume_pipe;

                $this->activeSections[$section->id]->fakt_volume = $this->activeSections[$section->id]->kalibr - $this->activeSections[$section->id]->volume;
                $this->activeSections[$section->id]->fakt_mass = ($this->activeSections[$section->id]->fakt_volume * $this->activeSections[$section->id]->density)/1000;
                $this->activeSections[$section->id]->diff_mass = ($this->activeSections[$section->id]->fakt_mass - $this->activeSections[$section->id]->mass)*1000;
                $this->activeSections[$section->id]->id_section = $section->id;
                $this->activeSections[$section->id]->name = $section->name;
            }
        }
    }

    public function loadDelivery($id)
    {
        $this->FuelDelivery = FuelDelivery::findOne($id);
        $this->id_trailer = $this->FuelDelivery->id_trailer;
        $this->date = $this->FuelDelivery->date;
        $this->id_fuel_module = $this->FuelDelivery->id_fuel_module;
        $this->id_fuel_module_section = $this->FuelDelivery->id_fuel_module_section;
        $this->driver = $this->FuelDelivery->driver;
        $this->id_product = $this->FuelDelivery->product->id;

        foreach ($this->FuelDelivery->fuelDeliverySections as $section)
        {
            $this->volume[$section->id] = $section->volume;
            $this->mass[$section->id] = $section->mass;
            $this->density[$section->id] = $section->density;
            $this->temp[$section->id] = $section->temp;
            $this->trailer_sections[$section->id] = $section->section->name;
            
            $trailer_section = $this->getSection($section->id_section);

            if ($trailer_section->volume != $section->kalibr)
                $this->pipe[$section->id] = 1;

            $this->activeSections[$section->id] = $section;
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