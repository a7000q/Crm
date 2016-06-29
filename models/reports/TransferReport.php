<?php 
namespace app\models\reports;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;
use app\models\Tranzactions;
use app\models\FuelModule;
use app\models\Terminals;
use app\models\Partners;
use app\models\Cards;
use app\models\AccessReport;
use app\models\Transfers;


class TransferReport extends Model
{
	public $sales;
	public $d1 =  false;
	public $d2 = false;
	public $module;
	public $sumLitr = 0;
	public $sumMoney = 0;

	public function rules()
    {
        return [
            [['d1', 'd2'], 'string'],
            [['module', 'company'], 'integer'],
            [['sumLitr'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           'd1' => "C",
           'd2' => "По", 
           'module' => 'Модуль',
           'company' => 'Компания'
        ];
    }

	public function getDataReport()
	{
		$transfers = Transfers::find();

		if (!$this->d1)
			$this->d1 = date("d.m.Y");
		

		$transfers = $transfers->andWhere(['>=', 'date', $this->d1Time]);

		if (!$this->d2)
			$this->d2 = date("d.m.Y");

		$transfers = $transfers->andWhere(['<=', 'date', $this->d2Time]);

		if ($this->module)
			$transfers = $transfers->andWhere(['in', 'id_terminal', $this->getTerminalsByModule($this->module)]);


		$transfers = $transfers->orderBy(["date" => SORT_ASC])->all();
		
		$res = "";

		$this->sumLitr = 0;
		$this->sumMoney = 0;

		foreach ($transfers as $trans)
		{
			$r["date"] = date("d M H:i", $trans->date);
			$r["cardNumber"] = $trans->tranzaction->card->id_txt;
			$r["name"] = $trans->tranzaction->card->name;
			$r["fuel_module_start"] = $this->getFuelModuleName($trans->tranzaction->section->module);
			$r["fuel_module_end"] = $this->getFuelModuleName($trans->section->module);
			$r["litr"] = $trans->tranzaction->doza;
			$r["id"] = $trans->id;
			$res[] = $r;
			$this->sumLitr += $trans->tranzaction->doza;
		}

		return $res;
	}

	public function getFuelModuleName($module = false)
	{
		if ($module != false)
		{
			$name = $module->name;

			return $name;
		}
		else
			return "";
	}

	public function getFuelModuleNameFull($module = false)
	{
		if ($module != false)
		{
			$name = $module->name.", ".$module->address;

			return $name;
		}
		else
			return "";
	}

	public function getD1Time()
	{
		return strtotime($this->d1);
	}

	public function getD2Time()
	{
		return strtotime($this->d2) + 86400;
	}

	public function getModules()
	{
		$modules = FuelModule::find()->all();
		$modules = ArrayHelper::map($modules, 'id', 'name');

		return $modules;
	}


	public function getTerminalsByModule($modules)
	{
		$Terminals = Terminals::find()->where(["in", 'id_fuel_module', $modules])->all();
		$Terminals = ArrayHelper::map($Terminals, 'id', 'id');

		return $Terminals;
	}

	public function getCardsByPartners($partners)
	{
		$partners = Cards::find()->where(['in', 'id_partner', $partners])->all();
		$partners = ArrayHelper::map($partners, 'id', 'id');

		return $partners;
	}

	



}