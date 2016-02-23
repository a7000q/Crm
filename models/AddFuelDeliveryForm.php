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

    public $volume = [];
    public $density = [];
    public $temp = [];
    public $mass = [];

	public function rules()
    {
        return [
            [['id_trailer', 'id_fuel_module', 'id_fuel_module_section'], 'required'],
            [['volume', 'density', 'temp', 'mass'], 'each', 'rule' => ['integer']]
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
            'mass' => 'Масса'
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

    public function createDelivery()
    {
        $user = Yii::$app->user->identity;

        foreach ($this->sections as $section)
        {
            if ($this->getActiveSection($section->id))
            {
                $FuelDelivery = new FuelDelivery();

                $FuelDelivery->id_section = $section->id;
                $FuelDelivery->id_user = $user->id;
                $FuelDelivery->date = time();
                $FuelDelivery->id_fuel_module_section = $this->id_fuel_module_section;
                $FuelDelivery->volume = $this->getParField($this->volume, $section->id);
                $FuelDelivery->density = $this->getParField($this->density, $section->id);
                $FuelDelivery->temp = $this->getParField($this->temp, $section->id);
                $FuelDelivery->mass = $this->getParField($this->mass, $section->id);

                //print_r($FuelDelivery);
                if ($FuelDelivery->validate())
                {
                    $FuelDelivery->save();
                    return true;
                }
            }
        }
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
}