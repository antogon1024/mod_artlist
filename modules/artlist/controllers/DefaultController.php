<?php

namespace app\modules\artlist\controllers;

use yii\web\Controller;
use Yii;
use app\modules\artlist\models\City;
//use app\modules\artlist\models\Materials;
use yii\helpers\Url;

/**
 * Default controller for the `artlist` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($id = null, $city_name = null)
    {
        if (empty($city_name)) {
            /*if ($city_id = Yii::$app->request->cookies->getValue('city')) {

                $city = \app\models\City::findOne($city_id);
                $this->city_iden = $city->id;
                $this->city_name = $city->name;
                $this->view->params['cities_name'] = $this->cities_name = $city->url;
            } else {
                $g = new \jisoft\sypexgeo\Sypexgeo();
                $city = City::find()->where(['name' => $g->get(Yii::$app->request->userIP)['city']['name_ru']])->one();

                $this->cities_name = ($city) ? $city->url : 'moscow';
            }*/


            $url = Url::to(['/artlist/default/index', 'city_name' => 'moscow']);
            return $this->redirect($url);
        }

        //$g = new \jisoft\sypexgeo\Sypexgeo();
        //echo '<pre>';print_r($g);exit;
        //$city = City::find()->where(['name' => $g->get(Yii::$app->request->userIP)['city']['name_ru']])->one();


        $this->view->params['city_name'] = $city_name = ($city_name) ? $city_name : 'moscow';

        $gorod = City::find()->where(['url' => $city_name])->one();

        return $this->render('index', [
            'gorod' => $gorod,
        ]);
    }
}
