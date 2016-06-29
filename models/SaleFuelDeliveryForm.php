<?php 
namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;

class SaleFuelDeliveryForm extends Model
{
    public $count_litrs;
    public $id_partner = false;
    public $id_fuel_module;
    public $id_fuel_module_section = false;
    public $id_card = false;
    public $date = false;
	
    public function rules()
    {
        return [
            [['count_litrs', 'id_partner', 'id_fuel_module'], 'required'],
           ['count_litrs', 'number'],
           [['id_partner', 'id_fuel_module', 'id_fuel_module_section', 'id_card', 'date'], 'integer'],
           ['dateText', 'date']
        ];
    }


    public function getDateText()
    {
        if ($this->date)
            return date("d.m.Y H:i", $this->date);
        else
            return "";
    }

    public function setDateText($value)
    {
        $this->date = strtotime($value);
    }

    public function init()
    {
        $this->date = "";
    }

    public function attributeLabels()
    {
        return [
            
        ];
    }

    public function sale()
    {
        $tranzaction = new Tranzactions();

        if (!$this->date)
            $this->date = time();

        $tranzaction->addTranzactionService($this->date, $this->id_fuel_module_section, $this->card->id, $this->count_litrs);
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
        if ($this->id_card == false)
            $card = Cards::find()->where(['id_partner' => $this->id_partner, 'id_txt' => 9999999, 'name' => 'XXXX', 'id_electro' => 'XXXX-'.$this->id_partner])->one();
        else
            $card = Cards::findOne($this->id_card);

        if (!$card)
        {
            $card = new Cards();
            $card->id_txt = 9999999;
            $card->id_electro = "XXXX-".$this->id_partner;
            $card->id_partner = $this->id_partner;
            $card->name = "XXXX";
            $card->save();
        }

        return $card;
    }

    public function getCards()
    {
        $cards = Cards::find()->where(['id_partner' => $this->id_partner])->andWhere(['<>', 'id_txt', 9999999])->andWhere(['<>', 'name', 'XXXX'])->andWhere(['<>', 'id_electro', "XXXX-".$this->id_partner])->all();
        $cards = ArrayHelper::map($cards, 'id', 'name', 'id_txt');

        return $cards;
    }

}