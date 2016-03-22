<?php 
namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;

class SaleFuelDeliveryForm extends Model
{
    public $count_litrs;
    public $id_partner;
    public $id_fuel_module;
    public $id_fuel_module_section = false;
	
    public function rules()
    {
        return [
            [['count_litrs', 'id_partner', 'id_fuel_module'], 'required'],
           ['count_litrs', 'number'],
           [['id_partner', 'id_fuel_module', 'id_fuel_module_section'], 'integer']
        ];
    }


    public function attributeLabels()
    {
        return [
            
        ];
    }

    public function sale()
    {
        $tranzaction = new Tranzactions();
        $tranzaction->addTranzactionService($this->id_fuel_module_section, $this->card->id, $this->count_litrs);
    }

    public function getFuelModules()
    {
        $modules = FuelModule::find()->rightJoin(Terminals::tableName(),'terminals.id_fuel_module = fuel_module.id')->all();
        $modules = ArrayHelper::map($modules, 'id', 'name');

        return $modules;
    }

    public function getFuelModuleSections()
    {
        if ($this->id_fuel_module != 0)
        {
            $module_sections = FuelModuleSections::find()->where(['id_module' => $this->id_fuel_module])->andWhere(["<>", "id_product", 0])->all();


            $module_sections = ArrayHelper::map($module_sections, 'id', 'name');
          

            return $module_sections;
        }
        else
            return false;
    }

    public function getPartners()
    {
        $section = FuelModuleSections::findOne($this->id_fuel_module_section);
        $id_product = $section->id_product;
        $partners = Partners::find()->rightJoin(Prices::tableName(),'prices.id_partner = partners.id')->where(['prices.id_product' => $id_product])->all();
        $partners = ArrayHelper::map($partners, 'id', 'name');

        return $partners;
    }

    public function getCard()
    {
        $card = Cards::find()->where(['id_partner' => $this->id_partner, 'id_txt' => '0', 'name' => 'XXXX', 'id_electro' => 'XXXX'])->one();

        if (!$card)
        {
            $card = new Cards();
            $card->id_txt = 0;
            $card->id_electro = "XXXX";
            $card->id_partner = $this->id_partner;
            $card->name = "XXXX";
            $card->save();
        }

        return $card;
    }

}