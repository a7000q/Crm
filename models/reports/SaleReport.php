<?php 
namespace app\models\reports;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;
use app\models\Tranzactions;

class SaleReport extends Model
{
	public $sales;

	public function getDataReport()
	{
		$tranzactions = Tranzactions::find()->where(["status" => "1"])->orderBy(["date" => SORT_ASC])->all();
		$res = "";

		foreach ($tranzactions as $tranz)
		{
			$r["date"] = date("d.m.Y H:i", $tranz->date);
			$r["cardNumber"] = $tranz->card->id_txt;
			$r["name"] = $tranz->card->name;
			$r["company"] = $tranz->card->partner->name;
			$r["fuel_module"] = $this->getFuelModuleName($tranz->section->module);
			$r["litr"] = $tranz->doza;
			$r["product_name"] = $tranz->tranzactionHistory->product_name;
			$r["price"] = $tranz->tranzactionHistory->price;
			$r["sum"] = $tranz->tranzactionHistory->sum;

			$res[] = $r;
		}

		return $res;
	}

	public function getFuelModuleName($module = false)
	{
		if ($module != false)
		{
			$name = $module->name;
			$address = $module->address;

			return $name." ".$address;
		}
		else
			return "";
	}


}