<?php

namespace app\components;

use app\models\user\UserType;
use Yii;

/**
 * Class City
 * @package app\components
 *
 */
class City
{
    /**
     * @var \jisoft\sypexgeo\Sypexgeo
     */
    private $geo;

    public $name;
    public $case;
    public $url;
    public $id;

    public function __construct()
    {
        $this->geo = new \jisoft\sypexgeo\Sypexgeo();
    }

    /**
     * @return array
     */
    public function getCity()
    {
        $ip = $this->getIp();

        return $this->geo->get($ip)['city'];
    }

    public function get()
    { //var_dump(Yii::$app->controller->route);die();
        if(Yii::$app->controller->route == 'site/index'){
          //  die('sc');
            $this->set();
        }
        elseif(Yii::$app->controller->route == 'site/user-city'){
            //var_dump(Yii::$app->request->getQueryParams()['city_name']);die();
            $city = \app\models\City::find()->where(['url' => Yii::$app->request->getQueryParams()['city_name']])->one();

            if(!Yii::$app->request->cookies->getValue('city') && $city){
                $this->set($city->id, false);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'was',
                    'value' => true,
                    'httpOnly' => false,
                    'expire' => time() + 60*60*24*90, // 90 дней
                ]));
            }
            elseif($city)
                $this->set($city->id, false);
        }
        elseif(Yii::$app->controller->route == 'site/category'){

            // var_dump(Yii::$app->request->getQueryParams()['city_name']);die();
            $city = \app\models\City::find()->where(['url' => Yii::$app->request->getQueryParams()['city_name']])->one();
           // if(!Yii::$app->request->cookies->getValue('city')){
                $this->set(@$city->id, false);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'was',
                    'value' => true,
                    'httpOnly' => false,
                    'expire' => time() + 60*60*24*90, // 90 дней
                ]));
           // }
           // elseif($city)
           //     $this->set(Yii::$app->request->cookies->getValue('city'), false);
        }
        elseif(Yii::$app->controller->route == 'site/personal-page'){
            $userType = UserType::findOne(['id' => Yii::$app->request->getQueryParams()['id']]);
            if(!Yii::$app->request->cookies->getValue('city')){
                $this->set($userType->city->id, false);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'was',
                    'value' => true,
                    'httpOnly' => false,
                    'expire' => time() + 60*60*24*90, // 90 дней
                ]));
            }
            else
                $this->set(Yii::$app->request->cookies->getValue('city'), false);
        }
        elseif(Yii::$app->controller->route == 'site/personal-albom'){
            $userType = UserType::findOne(['id' => Yii::$app->request->getQueryParams()['user_type_id']]);
            if(!Yii::$app->request->cookies->getValue('city')){
                $this->set($userType->city->id, false);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'was',
                    'value' => true,
                    'httpOnly' => false,
                    'expire' => time() + 60*60*24*90, // 90 дней
                ]));
            }
            else
                $this->set(Yii::$app->request->cookies->getValue('city'), false);
        }
        elseif($city_id = Yii::$app->request->cookies->getValue('city')){
            $city = \app\models\City::findOne($city_id);
            $this->id = $city->id;
            $this->name = $city->name;
            $this->case = $city->case;
            $this->url = $city->url;

            if (!$city) {
                $this->set();
            }
        }
        else{
            $this->set(null, false);
        }
    }

    /**
     * @return string
     */
    public function getIp()
    {
        if(Yii::$app->request->userIP == '127.0.0.1')
            return '195.19.132.64';
        else
            return $this->geo->ip;
    }

    /**
     * @param null $id
     * @param bool $redirect
     * @return bool|\yii\console\Response|\yii\web\Response
     */
    public function set($id = null, $redirect = true)
    {
        if ($id && ($city = \app\models\City::findOne($id)) != null){
            $city_id = $city->id;

            $this->id = $city->id;
            $this->name = $city->name;
            $this->case = $city->case;
            $this->url = $city->url;
        }
        else{

            $cityinfo = $this->getCity();

            if(($cityinfo && isset($cityinfo['name_ru']))&& $city = \app\models\City::find()->where(['name' => $cityinfo['name_ru']])->one()){
                $city_id = $city->id;

                $this->id = $city->id;
                $this->name = $city->name;
                $this->case = $city->case;
                $this->url = $city->url;
            }
            else{
                $city = $this->defaultCity();

                $city_id = $city->id;

                $this->id = $city->id;
                $this->name = $city->name;
                $this->case = $city->case;
                $this->url = $city->url;

            }
        }

        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'city',
            'value' => $city_id,
            'expire' => time() + 60*60*24*90, // 90 дней
        ]));

        //return ($redirect) ? Yii::$app->response->redirect(Url::to(["site/user-city", 'city_name' => $city->url]), 301) : false;
        return ($redirect) ? header('location: http://artlist/'.$city->url) : false;

    }

    public function getCityByCookies()
    {
        if ($city_id = Yii::$app->request->cookies->getValue('city')) {
            $city = \app\models\City::findOne($city_id);
            return $city;
        }
    }

    /**
     * @return \app\models\City|array|\yii\db\ActiveRecord
     */
    public function defaultCity()
    {
        $ip = $this->getIp();

        $data =  $this->geo->get($ip);

        $country = $data['country'];

        $city = $data['city'];

        if($country['name_ru'] == 'Россия')
            $defcity = \app\models\City::find()->where(['name' => 'Москва'])->one();
        elseif ($country['name_ru'] == 'Украина')
            $defcity = \app\models\City::find()->where(['name' => 'Киев'])->one();
        elseif ($country['name_ru'] == 'Белоруссия')
            $defcity = \app\models\City::find()->where(['name' => 'Минск'])->one();
        elseif ($country['name_ru'] == 'Казахстан')
            $defcity = \app\models\City::find()->where(['name' => 'Астана'])->one();
        else
            $defcity = \app\models\City::find()->where(['name' => 'Москва'])->one();

        return $defcity;
    }
}