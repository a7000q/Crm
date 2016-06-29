<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\Model;
use app\models\Tranzactions;
use yii\Helpers\ArrayHelper;
use app\models\Cards;
use app\models\AccessReport;


class AjaxController extends CController
{
   
    public function actionGetRealFuels()
    {
        $fuels = Tranzactions::find()->joinWith('realTimeTranzactions')->where(["real_time_tranzactions.status" => "fuel"]);

        if (Yii::$app->user->identity->role == 4)
        {
            $user = Yii::$app->user->identity;
            $AccessReport = AccessReport::find()->where(['id_user' => $user->id])->all();

            $partners = ArrayHelper::map($AccessReport, 'id', 'id_partner');
            $fuels = $fuels->andWhere(['in', 'id_card', $this->getCardsByPartners($partners)]);
        }

        $fuels = $fuels->all();

        $result = array();

        foreach ($fuels as $fuel)
        {
            $r = "";
            $r["doza"] = $fuel->realTimeTranzactions->doza;
            $r["name"] = $fuel->section->module->name;
            $r["cardName"] = $fuel->cardName;
            $r["partnerName"] = $fuel->partnerName;
            
            $result[] = $r;
        }

        return json_encode($result);
    }

    private function getCardsByPartners($partners)
    {
        $partners = Cards::find()->where(['in', 'id_partner', $partners])->all();
        $partners = ArrayHelper::map($partners, 'id', 'id');

        return $partners;
    }
 
}
