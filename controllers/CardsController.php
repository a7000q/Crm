<?php

namespace app\controllers;

use Yii;
use app\models\AddCardForm;
use app\models\BadTranzactions;
use app\models\Cards;
use yii\data\ActiveDataProvider;
use app\models\Partners;

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

    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Cards::find()->where(['id_partner' => $id]),
        ]);

        $partner = Partners::findOne($id);

        return $this->render('index', [
            'dataProvider' => $dataProvider, 'partner' => $partner
        ]);
    }

    public function actionUpdate($id)
    {
    	$model = Cards::findOne($id);

    	$post = Yii::$app->request->post();

    	if (isset($post["Cards"]))
    	{
    		$model->load($post);

    		if ($model->validate())
    		{
    			$model->save();
    			$this->redirect(['cards/index', 'id' => $model->id_partner]);	
    		}
    		

    	}

    	return $this->render('update', ['model' => $model]);
    }

    public function actionAddCard()
    {
        $card = new Cards();
        $post = Yii::$app->request->post();
        $success = false;

        if (isset($post["Cards"]))
        {
            $card->load($post);

            if ($card->convertAndSave())
            {
                $card = new Cards();
                $success = true;
            }
            
        }

        return $this->render('add-card', ['model' => $card, 'success' => $success]);
    }

}
