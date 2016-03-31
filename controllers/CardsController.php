<?php

namespace app\controllers;

use Yii;
use app\models\AddCardForm;
use app\models\BadTranzactions;

class CardsController extends CController
{
    public function actionCreateNewCard($id_electro)
    {
        $form = new AddCardForm();
        $form->id_electro = $id_electro;

        $post = Yii::$app->request->post();

        if (isset($post["AddCardForm"]))
        {
        	$form->load($post);

        	if ($form->validate())
        	{
        		$form->saveCard();

        		$this->redirect(['cards/bad-cards']);
        	}
        }

        return $this->render('create-new-card', ['model' => $form]);
    }

    public function actionBadCards()
    {
    	$cards = BadTranzactions::find()->orderBy(["date" => SORT_DESC])->all();

    	foreach ($cards as $k => $card) 
    	{
    		if ($card->isBaseCard)
    		{
    			unset($cards[$k]);
    		}
    	}

    	return $this->render('bad-cards', ['cards' => $cards]);
    }

    public function actionDeleteBadTranzaction($id)
    {
    	$tranz = BadTranzactions::findOne($id);
    	$tranz->delete();

    	$this->redirect(['cards/bad-cards']);
    }

}
