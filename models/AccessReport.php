<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "access_report".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_partner
 */
class AccessReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_partner'], 'required'],
            [['id_user', 'id_partner'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_partner' => 'Id Partner',
        ];
    }
}
