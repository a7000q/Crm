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
            [['doza'], 'number']
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

        $this->doza = $doza;
        $this->status = 1;
        $this->save();

        $r["status"] = "ok";

        return $r;
    }

    public function getSectionById($id)
    {
        return FuelModuleSections::findOne($id);
    }

    public function getTranzactionHistory()
    {
        return $this->hasOne(TranzactionsHistory::className(), ['id_tranzaction' => 'id'])->where(["type_tranzaction" => 1])->orderBy(["date" => SORT_DESC]);
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


    public static function findLastTranzaction($id_terminal, $id_electro)
    {
        $card = Cards::findOne(["id_electro" => $id_electro]);
        $terminal = Terminals::findOne($id_terminal);

        if (!$card)
            return $this->setError(2);

        if (!$terminal)
            return $this->setError(1);

        $tranzaction = Tranzactions::find()->where(['id_terminal' => $id_terminal, 'id_card' => $card->id, 'status' => 1])->orderBy(["date" => SORT_DESC])->one();

        if (!$tranzaction)
            return $this->setError(5);

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
        
        $TranzactionsHistory->sum = $this->tranzactionHistory->sum;
        $TranzactionsHistory->type_tranzaction = 2;
        $TranzactionsHistory->partner_name = $this->tranzactionHistory->partner_name;
        $TranzactionsHistory->card_name = $this->tranzactionHistory->card_name;
        $TranzactionsHistory->litr = $this->tranzactionHistory->litr;
        $TranzactionsHistory->price = $this->tranzactionHistory->price;
        $TranzactionsHistory->price_cost = $this->tranzactionHistory->price_cost;
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
        $TranzactionsHistory->price = $this->tranzactionHistory->price;
        $TranzactionsHistory->price_cost = $this->tranzactionHistory->price_cost;
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


}
