<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sections".
 *
 * @property integer $id
 * @property double $volume
 * @property double $volume_pipe
 * @property integer $id_trailer
 * @property integer $name
 */
class Sections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['volume', 'volume_pipe', 'id_trailer', 'name'], 'required'],
            [['volume', 'volume_pipe'], 'number'],
            [['id_trailer', 'name'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'volume' => 'Объем',
            'volume_pipe' => 'Объем трубы',
            'id_trailer' => 'Id Trailer',
            'name' => 'Название',
        ];
    }
}
