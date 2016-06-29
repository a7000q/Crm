<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\FuelModuleSections;
use yii\Helpers\ArrayHelper;
/**
 * This is the model class for table "fuel_module".
 *
 * @property integer $id
 * @property string $name
 */
class FuelModule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['name'], 'string', 'max' => 1000],
            [['address'], 'string', 'max' => 2000],
            [['coords'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'address' => 'Адрес',
            'terminal.id' => 'ID терминала',
            'tBalance' => 'Остаток',
            'averageLitr' => 'Средний объем за день',
            'maxLitr' => 'Max объем за день',
            'lastPrice' => 'Себестоимость'
        ];
    }

    public $averLitr = false;
    public $tBal = false;

   public function getFuelModuleSections()
    {
        return $this->hasMany(FuelModuleSections::className(), ['id_module' => 'id']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) 
        {
            
            if ($this->fuelModuleSections)
                foreach($this->fuelModuleSections as $section)
                    $section->delete();

            return true;
        } 
        else 
        {
            return false;
        }
    } 

    public function getTerminal()
    {
        return $this->hasOne(Terminals::className(), ['id_fuel_module' => 'id']);
    }

    static public function getModulesArray()
    {
        $modules = self::find()->all();
        $modules = ArrayHelper::map($modules, 'id', 'name');

        return $modules;
    }

    public function getComingSum($date = false)
    {
        $sum = 0;

        foreach ($this->fuelModuleSections as $section)
            $sum += $section->getComingSum($date);

        return $sum;
    }

    public function getSaleSum($date = false)
    {
        $sum = 0;

        foreach ($this->fuelModuleSections as $section)
            $sum += $section->getSaleSum($date);

        return $sum;
    }

    public function getTBalance($date = false)
    {
        $result = $this->getComingSum($date) - $this->getSaleSum($date);

        $this->tBal = $result;

        return number_format($result, 0);
    }

    public function getAverageLitr()
    {
        $sum = 0;
        $i = 0;

        foreach ($this->fuelModuleSections as $section)
        {
            $sum += $section->getAverageLitr();
            $i++;

        }

        if ($sum > 0 and $i > 0)
        	$sum = $sum/$i;

        $this->averLitr = $sum;

        return $sum;
    }

    public function getMaxLitr()
    {
		$doza = 0;

    	foreach ($this->fuelModuleSections as $section)
    	{
            if ($doza < $section->maxLitr)
            	$doza = $section->maxLitr;
    	}

    	return $doza;
    }

    public function getRequiredFuel()
    {
        if ($this->averLitr === false)
            $average = $this->averageLitr;
        else
            $average = $this->averLitr;

        if ($this->tBal === false)
            $balance = $this->tBalance;
        else
            $balance = $this->tBal;

        $requiredVolume = $average * 1.5;

        if ($requiredVolume >= $balance * 1000)
            return true;
        else
            return false;
    }

    public function getLastPrice()
    {
        $txt = "";
        foreach ($this->fuelModuleSections as $section)
        {
            $txt .= number_format($section->last_price, 2).",";
        }
        $txt = substr($txt, 0, -1);

        return $txt;
    }
}
