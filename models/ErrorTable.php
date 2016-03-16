<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "error_table".
 *
 * @property integer $id
 * @property string $name
 * @property string $rus_name
 */
class ErrorTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'error_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'rus_name'], 'required'],
            [['name', 'rus_name'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'rus_name' => 'Rus Name',
        ];
    }
}
