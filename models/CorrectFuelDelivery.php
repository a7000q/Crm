<?php 
namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;

class CorrectFuelDelivery extends Model
{

	public $sections;

    public function rules()
    {
        return [
            
        ];
    }


    public function attributeLabels()
    {
        return [
            
        ];
    }

    public function correctDelivery()
    {
        $FuelDelivery = FuelDelivery::find()->where(['<>', 'price', 0])->orderBy(['date' => SORT_ASC])->all();

        FuelModuleSections::resetBalance();
        foreach ($FuelDelivery as $delivery) 
        {
            if (!isset($this->sections[$delivery->id_fuel_module_section]))
            {
                $delivery->fuelModuleSection->balance = 0;
                $delivery->fuelModuleSection->last_price = 0;
                $delivery->fuelModuleSection->save();

                $this->sections[$delivery->id_fuel_module_section] = true;
            }

            $delivery->scenario = 'correct';
            $delivery->correctPrice();
            $delivery->save();

            $delivery->fuelModuleSection->updatePriceLitr($delivery->summDelivery, $delivery->fakt_volume);

            $delivery->fuelModuleSection->addLitr($delivery->fakt_volume);

            $tranzactions = Tranzactions::find()->joinWith('tranzactionHistory')->joinWith('terminal')->where(['<=', 'tranzactions.date', $delivery->nextDateOnSection])->andWhere(['>=', 'tranzactions.date', $delivery->date])->andWhere(['terminals.id_fuel_module' => $delivery->id_fuel_module])->groupBy('tranzactions_history.id_tranzaction')->all();

            foreach ($tranzactions as $tranzaction) 
            {
                if ($tranzaction->status == 1)
                {
                    $tranzaction->tranzactionHistory->price_cost = (string)number_format($delivery->fuelModuleSection->last_price, 2);
                    $tranzaction->tranzactionHistory->save();

                    
                    try 
                    {
                        $id_partner = $tranzaction->tranzactionHistory->id_partner;

                        $price = Prices::getPrice($id_partner, $tranzaction->section->id_product);
                        if ($price->id_type == 2)
                        {
                            $tranzaction->tranzactionHistory->price = (string)$price->getPriceP($tranzaction->section->id);
                            $tranzaction->tranzactionHistory->sum = ($tranzaction->doza * $tranzaction->price);
                            $tranzaction->tranzactionHistory->save();
                        }
                    }
                    catch (\Exception $ex)
                    {
                        echo $tranzaction->id." ".$ex."<br>";
                    }

                    
                    //print_r($tranzaction);

                    $delivery->fuelModuleSection->minusLitr($tranzaction->doza * 1);
                    //echo $tranzaction->id."<br>";
                }
            }
        }
    }
}