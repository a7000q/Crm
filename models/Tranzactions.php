<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for table "tranzactions".
 *
 * @property integer $id
 * @property integer $date
 * @property integer $status
 * @property integer $id_card
 * @property integer $id_terminal
 * @property string $doza
 */
class Tranzactions extends \yii\db\ActiveRecord
{
    public $id_electro;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tranzactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'id_card', 'id_terminal'], 'required'],
            [['date', 'status', 'id_card', 'id_terminal'], 'integer'],
            [['doza', 'h1', 'h2', 'd1', 'd2'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'status' => 'Status',
            'id_card' => 'Id Card',
            'id_terminal' => 'Id Terminal',
            'doza' => 'Doza',
        ];
    }

    public function addTranzactionService($id_section, $id_card, $doza)
    {
        $this->id_card = $id_card;
        $this->date = time();
        $this->status = 1;

        $section = $this->getSectionById($id_section);

        $this->id_terminal = $section->module->terminal->id;
        $this->doza = $doza;
        $this->save();

        $sumOrder = $this->card->pullMoney($section->product->id, $doza);
        $section->minusLitr($doza);


        $TranzactionsHistory = new TranzactionsHistory();
        $TranzactionsHistory->date = time();
        $TranzactionsHistory->id_tranzaction = $this->id;
        $TranzactionsHistory->id_partner = $this->card->id_partner;
        $TranzactionsHistory->sum = $sumOrder;
        $TranzactionsHistory->type_tranzaction = 1;
        $TranzactionsHistory->partner_name = $this->card->partner->name;
        $TranzactionsHistory->card_name = $this->card->name;
        $TranzactionsHistory->litr = $doza;
        $TranzactionsHistory->id_section = $id_section;
        $TranzactionsHistory->price_cost = (string)$section->last_price;

        $price = Prices::getPrice($this->card->id_partner, $section->id_product);

        $TranzactionsHistory->price = $price->price;
        $TranzactionsHistory->product_name = $section->product->short_name;
        $TranzactionsHistory->card_electro = $this->card->id_electro;
        $TranzactionsHistory->validate();
        
        $TranzactionsHistory->save();

    }

    public function createTranzaction($id_terminal, $id_electro)
    {
        $this->id_terminal = $id_terminal;
        $this->id_electro = $id_electro;
        

        if (!$this->terminal)
            return $this->setError(1, true);

        $card = Cards::findOne(["id_electro" => $id_electro]);

        if (!$card)
            return $this->setError(2, true);

        $this->id_card = $card->id;

        if (!$this->terminal->fuelModule->fuelModuleSections)
            return $this->setError(4, true);


        
        $this->date = time();

        $this->status = 0;

        $this->save();

        $sections = $this->terminal->fuelModule->fuelModuleSections;
       
        $res["status"] = 'ok';
        $res["tranzaction"] = $this->id;
        foreach ($sections as $section)
        {
            if ($section->isPrice($card->id_partner))
            {
                $r = "";
               
                $r["id_section"] = $section->id;
                $r["fuel_short"] = $section->product->short_name;
            
                $res['sections'][] = $r;
            }
        }
        
        return $res;
    }

    public function fill($id_section, $doza)
    {
        $section = $this->getSectionById($id_section);

        if (!($section and ($this->terminal->fuelModule->id == $section->id_module)))
            return $this->setError(6);

        if (!$this->card->isPermissionFuelDoza($section->product->id, $doza))
            return $this->setError(3);

        $sumOrder = $this->card->pullMoney($section->product->id, $doza, $section->id);
        $section->minusLitr($doza);


        $TranzactionsHistory = new TranzactionsHistory();
        $TranzactionsHistory->date = time();
        $TranzactionsHistory->id_tranzaction = $this->id;
        $TranzactionsHistory->id_partner = $this->card->id_partner;
        $TranzactionsHistory->sum = $sumOrder;
        $TranzactionsHistory->type_tranzaction = 1;
        $TranzactionsHistory->partner_name = $this->card->partner->name;
        $TranzactionsHistory->card_name = $this->card->name;
        $TranzactionsHistory->litr = $doza;
        $TranzactionsHistory->id_section = $id_section;
        $TranzactionsHistory->price_cost = (string)$section->last_price;

        $price = Prices::getPrice($this->card->id_partner, $section->id_product);

        $TranzactionsHistory->price = (string)$price->getPriceP($section->id);
        $TranzactionsHistory->product_name = $section->product->short_name;
        $TranzactionsHistory->card_electro = $this->card->id_electro;
        $TranzactionsHistory->validate();
       
        $TranzactionsHistory->save();

        $this->doza = $doza;
        $this->status = 1;
        $this->save();

        $this->calibrPrice();

        $r["status"] = "ok";

        return $r;
    }

    public function getSectionById($id)
    {
        return FuelModuleSections::findOne($id);
    }

    public function getTranzactionHistory()
    {
        return $this->hasOne(TranzactionsHistory::className(), ['id_tranzaction' => 'id'])->where(["type_tranzaction" => "1"])->orderBy(["date" => SORT_DESC]);
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminals::className(), ['id' => 'id_terminal']);
    }

    public function getCard()
    {
        return $this->hasOne(Cards::className(), ['id' => 'id_card']);
    }

    public function setError($id_error, $new = false)
    {
        if ($new)
        {
            $BadTranzactions = new BadTranzactions();
            $BadTranzactions->date = time();
            $BadTranzactions->id_terminal = $this->id_terminal;
            $BadTranzactions->id_electro = $this->id_electro;
            $BadTranzactions->id_error = $id_error;
            $BadTranzactions->validate();
        
            $BadTranzactions->save();
        }

        $error = ErrorTable::findOne($id_error);

        $result["status"] = "error";
        $result["msg"] = $error->name;

        return $result;
    }

    public static function setErrorStat($id_error, $new = false)
    {
        if ($new)
        {
            $BadTranzactions = new BadTranzactions();
            $BadTranzactions->date = time();
            $BadTranzactions->id_terminal = $this->id_terminal;
            $BadTranzactions->id_electro = $this->id_electro;
            $BadTranzactions->id_error = $id_error;
            $BadTranzactions->validate();
        
            $BadTranzactions->save();
        }

        $error = ErrorTable::findOne($id_error);

        $result["status"] = "error";
        $result["msg"] = $error->name;

        return $result;
    }



    public static function findLastTranzaction($id_terminal, $id_electro)
    {
        $card = Cards::findOne(["id_electro" => $id_electro]);
        $terminal = Terminals::findOne($id_terminal);

        if (!$card)
            return static::setErrorStat(2);

        if (!$terminal)
            return static::setErrorStat(1);

        $tranzaction = Tranzactions::find()->where(['id_terminal' => $id_terminal, 'id_card' => $card->id, 'status' => 1])->orderBy(["date" => SORT_DESC])->one();

        if (!$tranzaction)
            return static::setErrorStat(5);

        $r["status"] = 'ok';
        $r['doza'] = $tranzaction->doza;
        $r['tranzaction'] = $tranzaction->id;
        $r['product'] = $tranzaction->tranzactionHistory->product_name;

        return $r;
    }

    public function fuelBack($doza)
    {
        $diff = $this->doza - $doza*1;
        $this->doza = $doza;
        $this->save();

        $TranzactionsHistory = new TranzactionsHistory();
        $TranzactionsHistory->date = time();
        $TranzactionsHistory->id_tranzaction = $this->id;
        $TranzactionsHistory->id_partner = $this->tranzactionHistory->id_partner;
        $this->calibrPrice("end");
        $TranzactionsHistory->sum = $this->tranzactionHistory->sum;
        $TranzactionsHistory->type_tranzaction = 2;
        $TranzactionsHistory->partner_name = $this->tranzactionHistory->partner_name;
        $TranzactionsHistory->card_name = $this->tranzactionHistory->card_name;
        $TranzactionsHistory->litr = $this->tranzactionHistory->litr;
        $TranzactionsHistory->price = (string)$this->tranzactionHistory->price;
        $TranzactionsHistory->price_cost = (string)$this->tranzactionHistory->price_cost;
        $TranzactionsHistory->product_name = $this->tranzactionHistory->product_name;
        $TranzactionsHistory->card_electro = $this->tranzactionHistory->card_electro;
        $TranzactionsHistory->id_section = $this->tranzactionHistory->id_section;
        $TranzactionsHistory->validate();

        $TranzactionsHistory->save();

        $this->card->backMoney($this->tranzactionHistory->sum);
        $this->section->addLitr($TranzactionsHistory->litr);

        $sumOrder = $TranzactionsHistory->price*$doza;
        $this->card->partner->minusMoney($sumOrder);
        $this->section->minusLitr($doza);

        $TranzactionsHistory = new TranzactionsHistory();
        $TranzactionsHistory->date = time();
        $TranzactionsHistory->id_tranzaction = $this->id;
        $TranzactionsHistory->id_partner = $this->card->id_partner;
        $TranzactionsHistory->sum = $sumOrder;
        $TranzactionsHistory->type_tranzaction = 1;
        $TranzactionsHistory->partner_name = $this->card->partner->name;
        $TranzactionsHistory->card_name = $this->card->name;
        $TranzactionsHistory->litr = $doza;
        $TranzactionsHistory->id_section = $this->tranzactionHistory->id_section;
        $TranzactionsHistory->price = (string)$this->tranzactionHistory->price;
        $TranzactionsHistory->price_cost = (string)$this->tranzactionHistory->price_cost;
        $TranzactionsHistory->product_name = $this->section->product->short_name;
        $TranzactionsHistory->card_electro = $this->card->id_electro;

        $TranzactionsHistory->save();

        $this->doza = $doza;
        $this->status = 1;
        $this->save();

        $result["status"] = 'ok';
        return $result;
    }

    public function getSection()
    {
        return $this->hasOne(FuelModuleSections::className(), ['id' => 'id_section']);
    }

    public function getId_section()
    {
        return $this->tranzactionHistory->id_section;
    }


    public function calibrPrice($status = false)
    {
        if ($status == false)
        {
            $sensor = $this->getSensorSection();

            $this->h1 = $sensor["h"];
            $this->d1 = $sensor["d"];
            $this->save();
        }
        else if ($status == "end")
        {
            $sensor = $this->getSensorSection();
            $this->h2 = $sensor["h"];
            $this->d2 = $sensor["d"];
            $this->save();

            $l1 = TestCalibr::getLitrByH($this->h1);
            $l2 = TestCalibr::getLitrByH($this->h2);

            $doza_rashet = $l1 - $l2;

            if ($doza_rashet != 0)
            	$this->section->last_price = $this->section->last_price * ($this->doza/$doza_rashet);
            else
            	$this->section->last_price = 0;
            
            $this->section->save();
        }


    }

    public function getSensorSection()
    {
        $id_fuel_module_section = $this->tranzactionHistory->id_section;

        $sensor = Sensors::findOne(["id_fuel_module_section" => $id_fuel_module_section]);

        $i = 0;
        $h = 0;
        $d = 0;

        if ($sensor)
        {
        	$date = time();

	        $sensor_monitor = SensorMonitors::find()->where(["id_sensor" => $sensor->id])->andWhere(["<>", "fuel_level", 0])->orderBy(["date" => SORT_DESC])->one();

	        

	        if ($sensor_monitor)
            {
                $h = $sensor_monitor->fuel_level;
                $d = $sensor_monitor->density;
            }
        }

        

        $res["h"] = $h;
        $res["d"] = $d;

        return $res;
    }


}
