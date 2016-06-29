<?php 
namespace app\models\reports;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;
use app\models\Tranzactions;
use app\models\FuelModule;
use app\models\FuelModuleSections;
use app\models\Terminals;
use app\models\Partners;
use app\models\Cards;
use app\models\AccessReport;


class SaleReport extends Model
{
	public $sales;
	public $d1 =  false;
	public $d2 = false;
	public $module;
	public $company;
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

    public function getModuleSection()
    {
    	return FuelModuleSections::find()->where(['id_module' => $this->module])->one();
    }

	public function getDataReport()
	{
		$tranzactions = Tranzactions::find()->joinWith('terminal')->joinWith('tranzactionHistory')->where(["status" => "1"]);

		if (!$this->d1)
			$this->d1 = date("d.m.Y");
		

		$tranzactions = $tranzactions->andWhere(['>=', 'tranzactions.date', $this->d1Time]);

		if (!$this->d2)
			$this->d2 = date("d.m.Y");

		$tranzactions = $tranzactions->andWhere(['<=', 'tranzactions.date', $this->d2Time]);

		if ($this->module)
			$tranzactions = $tranzactions->andWhere(['terminals.id_fuel_module' => $this->module])->andWhere(['tranzactions_history.id_section' => $this->moduleSection->id]);

		if ($this->company)
			$tranzactions = $tranzactions->andWhere(['in', 'id_card', $this->getCardsByPartners($this->company)]);

		if (Yii::$app->user->identity->role == 4)
		{
			$user = Yii::$app->user->identity;
			$AccessReport = AccessReport::find()->where(['id_user' => $user->id])->all();

			$partners = ArrayHelper::map($AccessReport, 'id', 'id_partner');
			$tranzactions = $tranzactions->andWhere(['in', 'id_card', $this->getCardsByPartners($partners)]);
		}

		$tranzactions = $tranzactions->groupBy('tranzactions_history.id_tranzaction');

		$tranzactions = $tranzactions->orderBy(["tranzactions.date" => SORT_ASC])->all();
		
		$res = "";

		$this->sumLitr = 0;
		$this->sumMoney = 0;

		foreach ($tranzactions as $tranz)
		{
			$r["date"] = date("d M H:i", $tranz->date);
			if ($tranz->card)
				$r["cardNumber"] = $tranz->card->id_txt;
			else
				$r["cardNumber"] = "Карта удалена";

			$r["name"] = $tranz->cardName;
			$r["company"] = $tranz->partnerName;
			$r["fuel_module"] = $this->getFuelModuleName($tranz->section->module);

			if ($tranz->realTimeTranzactions && $tranz->realTimeTranzactions->status == "fuel")
			{
				$r["litr"] = $tranz->realTimeTranzactions->doza;
				$r['fuelStatus'] = $tranz->realTimeTranzactions->status;
			}
			else if ($tranz->realTimeTranzactions)
			{
				$r['fuelStatus'] = $tranz->realTimeTranzactions->status;
				$r["litr"] = $tranz->doza;
			}
			else
				$r["litr"] = $tranz->doza;

			$r["product_name"] = $tranz->tranzactionHistory->product_name;
			$r["price"] = $tranz->tranzactionHistory->price;
			$r["sum"] = $tranz->tranzactionHistory->sum;
			$r["id"] = $tranz->id;
			$res[] = $r;

			$this->sumLitr += $tranz->doza;
			$this->sumMoney += $r["sum"];
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

	public function getPartners()
	{
		$partners = Partners::find()->all();
		$partners = ArrayHelper::map($partners, 'id', 'name');

		return $partners;
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

	public function getDetail($id)
	{
		
		$tranz = Tranzactions::find();
		if (Yii::$app->user->identity->role == 4)
		{
			$user = Yii::$app->user->identity;
			$AccessReport = AccessReport::find()->where(['id_user' => $user->id])->all();

			$partners = ArrayHelper::map($AccessReport, 'id', 'id_partner');

			$tranz = $tranz->andWhere(['in', 'id_card', $this->getCardsByPartners($partners)]);
		}

		$tranz = $tranz->andWhere(['id' => $id]);
		$tranz = $tranz->one();

		$r = "";

		if ($tranz)
		{
			$r["date"] = date("d.m.Y H:i:s", $tranz->date);
			$r["cardNumber"] = $tranz->card->id_txt;
			$r["name"] = $tranz->card->name;
			$r["company"] = $tranz->card->partner->name;
			$r["fuel_module"] = $this->getFuelModuleNameFull($tranz->section->module);
			$r["litr"] = $tranz->doza;
			$r["product_name"] = $tranz->tranzactionHistory->product_name;
			$r["price"] = $tranz->tranzactionHistory->price;
			$r["sum"] = $tranz->tranzactionHistory->sum;
			$r["id"] = $tranz->id;
		}

		return $r;
	}



}