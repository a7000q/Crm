<?php

namespace app\models;

use Yii;
use app\models\Sections;
/**
 * This is the model class for table "trailers".
 *
 * @property integer $id
 * @property string $gos_number
 */
class Trailers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trailers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gos_number'], 'required'],
            [['gos_number'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gos_number' => 'Гос. номер',
        ];
    }

    public function getSections()
    {
        return $this->hasMany(Sections::className(), ['id_trailer' => 'id']);
    }

    


}
