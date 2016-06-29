<?php

namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "inpayment".
 *
 * @property integer $id
 * @property integer $date
 * @property integer $id_partner
 * @property string $sum
 * @property string $scan_plateg_src
 */
class Inpayment extends \yii\db\ActiveRecord
{
    public $file = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inpayment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'id_partner', 'sum'], 'required'],
            [['id', 'date', 'id_partner'], 'integer'],
            [['sum'], 'number'],
            [['scan_plateg_src'], 'string', 'max' => 1000],
            [['dateText'], 'date', 'format' => "php:d.m.Y"],
            [['file'], 'file'],
            [['scan_plateg_src'], 'default', 'value' => ""]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'id_partner' => 'Контрагент',
            'partner.name' => 'Контрагент',
            'sum' => 'Сумма',
            'scan_plateg_src' => 'Платежка',
            'dateText' => 'Дата',
            'file' => 'Скан. платежки'
        ];
    }

    public function getDateText()
    {
        return date("d.m.Y", $this->date);
    }


    public function setDateText($value)
    {
       $this->date = strtotime($value);
    }

    public function init()
    {
        $this->date = time();
    }

    public function getPartners()
    {
        $partners = Partners::find()->all();

        $partners = ArrayHelper::map($partners, 'id', 'name');
        return $partners;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
     
            if ($this->file)
                $this->saveFile();

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->partner->calcBalance();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->partner->calcBalance();
    }

    private function saveFile()
    {
        $dir = 'uploads/inpayments/'.$this->id_partner.'/';

        if (!is_dir($dir))
                mkdir($dir, 0777, true);

        $file_name = date("d-m-Y")."_".$this->file->name;

        $this->scan_plateg_src = $dir.$file_name;

        $this->file->saveAs($this->scan_plateg_src);
    }

    public function getPartner()
    {
        return $this->hasOne(Partners::className(), ['id' => 'id_partner']);
    }
 

}
