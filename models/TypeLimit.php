<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type_limit".
 *
 * @property integer $id
 * @property string $name
 */
class TypeLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_limit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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

    public function getPeriod()
    {
        $res = "";
        switch ($this->id) {
            case 1:
                $res = $this->dayPeriod();
                break;
            case 2:
                $res = $this->weekPeriod();
                break;
            case 3:
                $res = $this->monthPeriod();
                break;
            default:
                $res = $this->dayPeriod();
                break;
        }

        return $res;
    }

    public function dayPeriod()
    {
        $now_date = date("d.m.Y");

        $d1 = strtotime($now_date." 00:00:00");
        $d2 = strtotime($now_date." 23:59:59");

        $result["start"] = $d1;
        $result["end"] = $d2;

        return $result;
    }

    public function weekPeriod()
    {
        $d1 = strtotime("last Monday");
        $d2 = strtotime("next Thursday");

        $d1 = date("d.m.Y", $d1)." 00:00:00";
        $d2 = date("d.m.Y", $d2)." 23:59:59";

        $result["start"] = strtotime($d1);
        $result["end"] = strtotime($d2);

        return $result;
    }

    public function monthPeriod()
    {
        $d1 = date("1.m.Y 00:00:00");
        $d2 = date("t.m.Y 23:59:59");

        $result["start"] = strtotime($d1);
        $result["end"] = strtotime($d2);

        return $result;
    }
}
