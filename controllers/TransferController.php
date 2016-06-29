<?php

namespace app\controllers;

use Yii;
use app\models\Tranzactions;
use app\models\Transfers;

class TransferController extends CController
{
    public function actionIndex()
    {
        $Tranzactions = Tranzactions::find()->where(['status' => 3])->orderBy(['date' => SORT_ASC])->all();

        return $this->render('index', ['model' => $Tranzactions]);
    }

    public function actionTransferSave($id)
    {
        $tranzaction = Tranzactions::findOne($id);

        $transfer = new Transfers();
        $transfer->scenario = 'addTransfer';
        $transfer->id_tranzaction = $id;
        $transfer->date = time();

        $post = Yii::$app->request->post();

        if (isset($post["Transfers"]))
        {
            $transfer->load($post);
            if ($transfer->id_module && count($transfer->sections) == 1)
            {
                $keys = array_keys($transfer->sections);
                $transfer->id_section = $keys[0];
            }
        }

        if (isset($post["saveTransfer"]))
        {
            if ($transfer->validate())
            {
                $transfer->save();
                $tranzaction->status = 4;
                $tranzaction->save();
                return $this->redirect(['transfer/index']);
            }
        }

        return $this->render('transfer-save', ['model' => $tranzaction, 'transfer' => $transfer]);
    }

    public function actionApiDelete($id)
    {
        $transfer = Transfers::findOne($id);
        $transfer->delete();

        echo "ok";
    }
}
