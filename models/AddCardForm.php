<?php 
namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;

class AddCardForm extends Model
{
	   
    public $id_electro;
    public $name;
    public $id_partner;
    public $id_txt;

    public function rules()
    {
        return [
            [['id_electro', 'id_partner', 'name', 'id_txt'], 'required'],
            [['id_partner', 'id_txt'], 'integer'],
            [['id_electro', 'name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_partner' => 'Контрагент',
            'id_txt' => 'Текстовый номер карты',
            'name' => 'Название карты'
        ];
    }

    public function getPartners()
    {
        $partners = Partners::find()->all();
        $partners = ArrayHelper::map($partners, 'id', 'name');

        return $partners;
    }

    public function saveCard()
    {
        $card = new Cards();
        $card->id_txt = $this->id_txt;
        $card->id_electro = $this->id_electro;
        $card->id_partner = $this->id_partner;
        $card->name = $this->name;

        $card->save();

        $tranz = BadTranzactions::find()->all();
        foreach ($tranz as $t) {
            $t->delete();
        }
    }
}