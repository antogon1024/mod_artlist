<?php

namespace app\components\competition;

use app\models\user\UserType;
use app\models\UserDialog;
use Yii;
use yii\base\Widget;


class CompetitionFlashWidget extends Widget
{
    protected $show = false;

    protected $message;

    public function run()
    {
        if (Yii::$app->session->hasFlash('competition')){
            $this->message = Yii::$app->session->getFlash('competition');
            $this->show = true;
        }
        return $this->render('index', [
            'message' =>  $this->message,
            'show' => $this->show,
        ]);
    }
}