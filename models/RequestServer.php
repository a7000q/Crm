<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requestServer".
 *
 * @property integer $id
 * @property integer $date
 * @property string $url
 * @property string $ip
 */
class RequestServer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requestServer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'url', 'ip'], 'required'],
            [['date'], 'integer'],
            [['url', 'ip'], 'string', 'max' => 1000]
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
            'url' => 'Url',
            'ip' => 'Ip',
        ];
    }
}
