<?php 
namespace app\models\reports;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;
use app\models\TerminalErrors;
use app\models\FuelModule;
use app\models\Terminals;

class ErrorReport extends Model
{

	public $d1 =  false;
	public $d2 = false;
	public $module;
	public $company;

	public function rules()
	{
		return [
			[['d1', 'd2'], 'string'],
			[['module', 'company'], 'integer']
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

	public function getDataReport()
	{
		$errors = TerminalErrors::find();

		if (!$this->d1)
			$this->d1 = date("d.m.Y");

		if (!$this->d2)
			$this->d2 = date("d.m.Y");

		$errors = $errors->andWhere(['>=', 'date', $this->d1Time]);

		$errors = $errors->andWhere(['<=', 'date', $this->d2Time]);

		if ($this->module)
			$errors = $errors->andWhere(['in', 'id_terminal', $this->getTerminalsByModule($this->module)]);

		$errors = $errors->orderBy(["date" => SORT_ASC])->all();

		$res = "";

		foreach ($errors as $error)
		{
			$r["date"] = $error->dateText;
			$r["module"] = $error->terminal->fuelModule->name;
			$r["terminal"] = $error->terminal->name;
			$r["text"] = $error->text;
			$r["id"] = $error->id;

			$res[] = $r;
		}

		return $res;
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
}