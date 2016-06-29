<?php

namespace app\models;

use Yii;
use app\models\Products;
use yii\Helpers\ArrayHelper;
use app\models\Prices;
use app\models\FuelDelivery;
use app\models\Tranzactions;

/**
 * This is the model class for table "fuel_module_sections".
 *
 * @property integer $id
 * @property integer $id_module
 * @property integer $name
 * @property double $volume
 * @property double $id_product
 */
class FuelModuleSections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
    
     */

    public $cSum = false;
    public $sSum = false;

    public $sDate = false;

    public $tMaxLitr = false;

    public static function tableName()
    {
        return 'fuel_module_sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_module', 'name', 'volume', 'id_product'], 'required'],
            [['id_module', 'name'], 'integer'],
            [['volume', 'id_product', 'balance', 'balance_fact'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_module' => 'Id Module',
            'name' => 'Название',
            'volume' => 'Обьем',
            'id_product' => 'Продукт',
            'product.name' => 'Продукт',
            'balance' => 'Баланс'
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'id_product']);
    }

    public function getModule()
    {
        return $this->hasOne(FuelModule::className(), ['id' => 'id_module']);
    }

    public function getSensor()
    {
        return $this->hasOne(Sensors::className(), ['id_fuel_module_section' => 'id']);
    }

    public function getProducts()
    {
        $products = Products::find()->all();

        if ($products)
            $products = ArrayHelper::map($products, 'id', 'name');
        else
            $products = false;

        return $products;
    }

    public function isPrice($id_partner)
    {
        $price = Prices::findOne(['id_product' => $this->id_product, 'id_partner' => $id_partner]);

        if ($price)
            return true;
        else
            return false;
    }

    public function addLitr($volume)
    {
        $this->balance = $this->balance + $volume;
        $this->save();
    }

    public function minusLitr($volume)
    {
        $this->balance = $this->balance - $volume;
        $this->save();
    }

    public function updatePriceLitr($sum_delivery, $volume_delivery)
    {
        $this->last_price = ($sum_delivery + $this->balance * $this->last_price)/($this->balance + $volume_delivery);
        $this->save();
    }

    public function updateDensity($mass, $volume)
    {
        $density = $mass*1000/$volume;
        $this->last_density = $density;
        $this->save();
    }

    public function getNowDensity()
    {
        if ($this->sensor)
        {
            $sensor_monitor = SensorMonitors::find()->where(["id_sensor" => $this->sensor->id])->andWhere(["<>", 'fuel_level', '0'])->andWhere(["<>", 'density', '0'])->orderBy(["date" => SORT_DESC])->one();

            if ($sensor_monitor)
            {
                return $sensor_monitor->density;
            }
            else
                return 0;
        }
        else
            return 0;
    }

    public function updatePriceDensityForBay()
    {
        $diff_density = $this->last_density - $this->nowDensity;

        $oneProc = $this->last_density/100;

        $diff_proc = $diff_density * $oneProc;

        $oneProcPrice = $this->last_price/100;

        $this->last_price = $this->last_price + ($diff_proc * $oneProcPrice);

        $this->last_density = $this->nowDensity;

        $this->save();
    }

    static public function getSections($id_module)
    {
        $sections = self::find()->where(['id_module' => $id_module])->all();
        $sections = ArrayHelper::map($sections, 'id', 'name');

        return $sections;
    }

    static public function resetBalance()
    {
        $sections = self::find()->all();
        foreach ($sections as $section)
        {
            $section->balance = 0;
            $section->save();
        }
    }

    public function getComingSum($date = false)
    {
        if ($this->cSum === false or $this->sDate != $date)
        {
	        if ($date == false)
	            $date = time(); 

	        $this->sDate = $date;

	        $FuelDeliverySum = FuelDelivery::find()->where(['id_fuel_module_section' => $this->id])->andWhere(['<=', 'date', $date])->sum('fakt_volume');

	        $TransferSum = Transfers::find()->joinWith('tranzaction')->where(['transfers.id_section' => $this->id])->andWhere(['<=', 'tranzactions.date', $date])->sum('tranzactions.doza');

	        $sum = $FuelDeliverySum + $TransferSum;

	        $this->cSum = $sum;
       	}
       	else
       		$sum = $this->cSum;

        return $sum;

    }

    public function getSaleSum($date = false)
    {
    	if ($this->sSum === false or $this->sDate != $date)
    	{
	    	if ($date == false)
	            $date = time(); 

	        $this->sDate = $date;

	    	$tranzactionsSum = Tranzactions::find()->joinWith('tranzactionHistory')->joinWith('terminal')->where(['<=', 'tranzactions.date', $date])->andWhere(['terminals.id_fuel_module' => $this->id_module])->groupBy('tranzactions_history.id_tranzaction')->sum('doza');

	    	$this->sSum = $tranzactionsSum;
	    }
	    else
	    	$tranzactionsSum = $this->sSum;

    	return $tranzactionsSum;
    }

    public function getAllTranzactions($date1, $date2)
    {
        $tranzactions = Tranzactions::find()->joinWith('tranzactionHistory')->joinWith('terminal')->where(['>=', 'tranzactions.date', $date1])->andWhere(['<=', 'tranzactions.date', $date2])->andWhere(['terminals.id_fuel_module' => $this->id_module])->groupBy('tranzactions_history.id_tranzaction')->all();

        return $tranzactions;
    }

    public function getAllFuelDelivery($date1, $date2)
    {
        $FuelDelivery = FuelDelivery::find()->where(['id_fuel_module_section' => $this->id])->andWhere(['>=', 'date', $date1])->andWhere(['<=', 'date', $date2])->all();

        return $FuelDelivery;
    }

    public function getAllTransfers($date1, $date2)
    {
        $Transfers = Transfers::find()->joinWith('tranzaction')->where(['transfers.id_section' => $this->id])->andWhere(['>=', 'tranzactions.date', $date1])->andWhere(['<=', 'tranzactions.date', $date2])->all();

        return $Transfers;
    }

    public function getTBalance($date = false)
    {
    	return $this->getComingSum($date) - $this->getSaleSum($date);
    }

    public function getAverageLitr()
    {
       $date1 = new \DateTime();
       $date1->modify("-1 month");
       

       $date2 = new \DateTime();
       $interval = $date1->diff($date2);

       $count = $interval->format('%a');

       $d1 = $date1;

       $j = 0;

       $sum = 0;

       $tMaxLitr = 0;

       for ($i = 1; $i <= $count; $i++)
       {
       		$dt1 = $d1->format('d.m.Y 00:00:00');
       		$dt2 = $d1->format('d.m.Y 23:59:59');

       		$time1 = strtotime($dt1);
       		$time2 = strtotime($dt2);

       		$tSum = Tranzactions::find()->joinWith('tranzactionHistory')->joinWith('terminal')->where(['>=', 'tranzactions.date', $time1])->andWhere(['<=', 'tranzactions.date', $time2])->andWhere(['tranzactions_history.id_section' => $this->id])->andWhere(['terminals.id_fuel_module' => $this->id_module])->groupBy('tranzactions_history.id_tranzaction')->sum('doza');

       		if ($tSum > 0)
       		{
       			$sum += $tSum;
       			$j++;
       		}

       		if ($tMaxLitr < $tSum)
       			$tMaxLitr = $tSum;

       		$d1->modify('+1 day');
       }

       if ($j > 0)
       		$result = ceil($sum/$j);
       	else
       		$result = 0;


       $this->tMaxLitr = $tMaxLitr;
       
       return $result;
      
        
    }

    public function getMaxLitr()
    {
       if ($this->tMaxLitr === false)
       {
	       $date1 = new \DateTime();
	       $date1->modify("-1 month");
	       

	       $date2 = new \DateTime();
	       $interval = $date1->diff($date2);

	       $count = $interval->format('%a');

	       $d1 = $date1;

	       $sum = 0;

	       for ($i = 1; $i <= $count; $i++)
	       {
	       		$dt1 = $d1->format('d.m.Y 00:00:00');
	       		$dt2 = $d1->format('d.m.Y 23:59:59');

	       		$time1 = strtotime($dt1);
	       		$time2 = strtotime($dt2);

	       		$tSum = Tranzactions::find()->joinWith('tranzactionHistory')->joinWith('terminal')->where(['>=', 'tranzactions.date', $time1])->andWhere(['<=', 'tranzactions.date', $time2])->andWhere(['tranzactions_history.id_section' => $this->id])->andWhere(['terminals.id_fuel_module' => $this->id_module])->groupBy('tranzactions_history.id_tranzaction')->sum('doza');

	       		if ($sum < $tSum)
	       		{
	       			$sum = $tSum;
	       		}

	       		$d1->modify('+1 day');
	       }

	       return $sum;

   	   }
   	   else
   	   		return $this->tMaxLitr;

    }


}
