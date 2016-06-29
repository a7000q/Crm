<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use app\models\Tranzactions;

/**
 * This is the model class for table "photos".
 *
 * @property integer $id
 * @property integer $id_tranzaction
 * @property string $src
 */
class Photos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file;


    public static function tableName()
    {
        return 'photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tranzaction', 'src'], 'required'],
            [['id_tranzaction'], 'integer'],
            [['src'], 'string', 'max' => 1000],
            [['file'], 'file']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tranzaction' => 'Id Tranzaction',
            'src' => 'Src',
        ];
    }

    public function upload()
    {
        if ($this->hasTranzaction())
        {
            $srcFile = $this->srcFile;
            $srcFile .= $this->file->baseName . '.' . $this->file->extension;
            $this->src = $srcFile;
            if ($this->validate()) {
                $this->file->saveAs($srcFile);
                $this->save();
                return true;
            } else {
                return false;
            }
        }
        else
            return false;
        
        //print_r($this->file);
        //die();
    }

    public function hasTranzaction()
    {
        $Tranzaction = Tranzactions::findOne($this->id_tranzaction);

        if ($Tranzaction)
            return true;
        else
            return false;
    }

    public function getTranzaction()
    {
        return $this->hasOne(Tranzactions::className(), ['id' => 'id_tranzaction']);
    }

    public function getSrcFile()
    {
        $src = "uploads/photos/".$this->tranzaction->id_partner."/";

        if (!file_exists($src))
            mkdir($src);

        $src .= $this->tranzaction->id_card."/";

        if (!file_exists($src))
            mkdir($src);

        $src .= $this->id_tranzaction."/";
        if (!file_exists($src))
            mkdir($src);


        return $src;
    }
}
