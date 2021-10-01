<?php

namespace app\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Url as UrlHelper;
use app\models\Url as UrlModel;

use dastanaron\translit\Translit;
use Yii;

class Url
{
    private static $arrUrl;

    /**
     * @param array $url
     * @return array|string
     * @throws \Throwable
     */
    public static function to($url = [])
    {
        $f = false;
        $temp_url = 0;

        $cname = @$url['city_name'];

        $url = UrlHelper::to($url);

        $city_id = Yii::$app->request->cookies->getValue('city');

        //$city = \app\models\City::findOne($city_id);

        $city = \app\modules\artlist\models\City::getDb()->cache(function ($db) use ($city_id) {
            return \app\modules\artlist\models\City::find()->where(['id' => $city_id])->one();
        }, 5);

        if($city){
            $city_name = $city->url;
        }
        else{
            $city_name = 'moscow';
        }

       $city_name = $cname;

        if(strripos($url, 'photographers/id')){
            $string_array = explode("id", $url);
            $temp_url = '/photographers/id'.$string_array[1];
            $f = true;
        }
        if(empty(self::$arrUrl)){
            self::$arrUrl = ArrayHelper::map(UrlModel::find()->select('redirect, referer')->asArray()->all(),  'referer', 'redirect');
        }
        if(!empty(self::$arrUrl[$url]) || !empty(self::$arrUrl[$temp_url]) ){

            if($f){
                return '/'.$city_name.self::$arrUrl[$temp_url];
            }
            return $city_name.self::$arrUrl[$url];
        }
        else return $url;
    }

    public static function toRoute($url = [])
    {
        $url = UrlHelper::toRoute($url);
        if(empty(self::$arrUrl)){
            self::$arrUrl = ArrayHelper::map(UrlModel::find()->select('redirect, referer')->asArray()->all(),  'referer', 'redirect');
        }
        return !empty(self::$arrUrl[$url]) ? self::$arrUrl[$url]: $url;
    }
}
