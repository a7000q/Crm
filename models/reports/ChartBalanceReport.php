<?php 
namespace app\models\reports;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;
use app\models\FuelModule;


class ChartBalanceReport extends Model
{

	public $d1 =  false;
	public $d2 = false;
	public $module = false;

	public $vStart;
	public $vEnd;

	public $data = false;
	public $litrs;
	public $sensor = 0;

	private $monitors = false;


	public function rules()
	{
		return [
			[['d1', 'd2'], 'string'],
			[['module'], 'integer'],
			[['d1', 'd2', 'module'], 'required']
		];
	}

	public function attributeLabels()
    {
        return [
           'd1' => "C",
           'd2' => "По", 
           'module' => 'Модуль'
        ];
    }

	public function getD1Time()
	{
		return strtotime($this->d1." 00:00:00");
	}

	public function getD2Time()
	{
		return strtotime($this->d2) + 86400;
	}

	public function formData()
	{
		
		$dt1 = $this->d1Time;
		$dt2 = $this->d2Time;


		$this->module = FuelModule::findOne($this->module);

		$this->vStart = str_replace(",", ".", $this->module->getTBalance($dt1));
		$this->vEnd = str_replace(",", ".", $this->module->getTBalance($dt2));

		$this->litrs = $this->vStart;

		$section = $this->module->fuelModuleSections[0];

		$result = "";

		$tranzactions = $section->getAllTranzactions($dt1, $dt2);
		$deliveryes = $section->getAllFuelDelivery($dt1, $dt2);
		$transfers = $section->getAllTransfers($dt1, $dt2);

		
		$mas = 10000;

		if ($section->sensor)
		{
			$arr_sensor = $section->sensor->getMonitors($dt1, $dt2);
			$this->monitors = ArrayHelper::map($arr_sensor, 'date', 'fuel_level');
		}
		
		

		foreach ($tranzactions as $tranzaction)
		{ 
			$result[$tranzaction->date]['litr'] = $tranzaction->doza * (-1);
			$result[$tranzaction->date]['sensor'] = $this->getSensorValue($tranzaction->date) * $mas;
		}

		foreach ($deliveryes as $delivery)
		{
			$result[$delivery->date]['litr'] = $delivery->fakt_volume;
			$result[$delivery->date]['sensor'] = $this->getSensorValue($delivery->date) * $mas;
		}

		foreach ($transfers as $transfer)
		{
			$result[$transfer->date]['litr'] = $transfer->tranzaction->doza;
			$result[$transfer->date]['sensor'] = $this->getSensorValue($transfer->date) * $mas;
		}

		ksort($result);

		$this->data = $result;

		

		//die($this->vStart." ".$this->vEnd);
	}

	public function getModules()
	{
		$modules = FuelModule::find()->all();
		$modules = ArrayHelper::map($modules, 'id', 'name');

		return $modules;
	}

	public function getSensorValue($dt)
	{
		if ($this->monitors)
		{	
			$j = 0;
			$i = $dt;
			while (!isset($this->monitors[$i]) and $j <= 1000) {
				$i++;
				$j++;
			} 

			if (isset($this->monitors[$i]))
				return $this->monitors[$i];
			else
				return 0;
		}

		return 0;
	}





}