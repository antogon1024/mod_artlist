<?php

namespace app\components\message;

use app\models\user\UserType;
use app\models\UserDialog;
use Yii;
use yii\base\Widget;


class MessageWidget extends Widget
{
    public function run()
    {
        $mass_in_home = false;
        $height = '';

        if (!Yii::$app->user->isGuest) {

            $userdialogs = UserDialog::find()
                ->where(['user_id' =>  UserType::getUserType()->id])
                ->andWhere(['visible' => 1, 'readed' => 0, 'popup' => 1])
                ->orderBy('updated_at DESC')
                ->all();

            if($userdialogs) $mass_in_home = true;

        } else $userdialogs = [];

        foreach ($userdialogs as $userdialog) {
            if($userdialog->dialog->lastmessage->notice){
                $height = '90vh';
            }
        }
        return $this->render('index', [
            'mass_in_home' => $mass_in_home,
            'userdialogs' => $userdialogs,
            'height' => $height,
            'max-height' => $height,
        ]);
    }
}