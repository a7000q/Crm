<?php 
namespace app\models\reports;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;
use app\models\FuelDelivery;

class BayReport extends Model
{
	public $sales;

	public function getDataReport()
	{
		$FuelDelivery = FuelDelivery::find()->where(["<>", "price", 0])->orderBy(["date" => SORT_ASC])->all();
		$res = "";

		foreach ($FuelDelivery as $fuel)
		{
			$r["date"] = date("d.m.Y H:i", $fuel->date);
			$r["gos_number"] = $fuel->gos_number;
			$r["company"] = $fuel->partner->name;
			$r["fuel_module"] = $this->getFuelModuleName($fuel->fuelModule);
			$r["litr"] = $fuel->fakt_volume;
			$r["product_name"] = $fuel->product->short_name;
			$ssum = ($fuel->price+$fuel->price_track)*$fuel->mass;
			$r["sum"] = number_format($ssum, 2, ".", "  ");
			$r["price"] = number_format($ssum/$fuel->fakt_volume, 2);

			$res[] = $r;
		}

		return $res;
	}

	public function getFuelModuleName($module)
	{
		$name = $module->name;
		$address = $module->address;

		return $name." ".$address;
	}
}