<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "drivers".
 *
 * @property integer $id
 * @property string $name
 */
class Drivers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drivers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 1000],
            ['name', 'unique']
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
        ];
    }

    public function addDriver($driver)
    {
        $result = static::findOne(['name' => $driver]);

        if (!$result)
        {
    
            $this->name = $driver;

            if ($this->validate())
                $this->save();
        }
    }
}
