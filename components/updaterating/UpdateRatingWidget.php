<?php

namespace app\components\updaterating;

use app\modules\artlist\models\user\UserType;
use app\models\UserDialog;
use Yii;
use yii\base\Widget;


class UpdateRatingWidget extends Widget
{
    protected $show = false;

    protected $message;

    public function run()
    {
		
        //$user = UserType::getUserType();
		
        $info = UserType::getRatingFlashInfo();
		
        return $this->render('index', [
            'ratingInfo' =>  $info
        ]);
    }
}