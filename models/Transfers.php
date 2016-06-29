<?php

namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use app\models\FuelModule;
use app\models\Tranzactions;
use app\models\FuelModuleSections;

/**
 * This is the model class for table "transfers".
 *
 * @property integer $id
 * @property integer $date
 * @property integer $id_tranzaction
 * @property integer $id_section
 */
class Transfers extends \yii\db\ActiveRecord
{
    public $id_module;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'id_tranzaction', 'id_section'], 'required'],
            [['date', 'id_tranzaction', 'id_section', 'id_module'], 'integer'],
            [['date', 'id_tranzaction', 'id_section', 'id_module'], 'required', 'on' => 'addTransfer']
        ];
    }

    /**
     * @inheritdoc
     */


    public function scenarios()
    {
        return [
            'addTransfer' => ['id_module', 'id_section', 'id_tranzaction', 'date']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'id_tranzaction' => 'Id Tranzaction',
            'id_section' => 'Id Section',
            'id_module' => 'Выберите топливный модуль'
        ];
    }

    public function getSections()
    {
        return FuelModuleSections::getSections($this->id_module);
    }

    public function getModules()
    {
        return FuelModule::getModulesArray();
    }

    public function getTranzaction()
    {
        return $this->hasOne(Tranzactions::className(), ['id' => 'id_tranzaction']);
    }

    public function getSection()
    {
        return $this->hasOne(FuelModuleSections::className(), ['id' => 'id_section']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->tranzaction)
                $this->tranzaction->delete();
            
            return true;
        } else {
            return false;
        }
    }
}
