<?php

namespace app\modules\artlist\controllers;

use dastanaron\translit\Translit;
use yii\db\Expression;
use yii\web\Controller;
use Yii;
use app\modules\artlist\models\City;
use app\modules\artlist\models\Blocks;
use app\modules\artlist\models\Type;
use app\modules\artlist\models\user\UserType;
use app\modules\artlist\models\Genre;
use yii\helpers\Url;
use app\modules\artlist\models\user\UserMedia;

/**
 * Default controller for the `artlist` module
 */
class DefaultController extends Controller
{
    /*public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60*60*24,
                'variations' => [
                    //\Yii::$app->language,
                    Yii::$app->request->get('city_name'),
                    Yii::$app->params['rand'],
                    //Yii::$app->request->get('ng'),
                ],
                //'dependency' => [
                //'class' => 'yii\caching\DbDependency',
                //'sql' => 'SELECT COUNT(*) FROM post',
                //],
            ],
        ];
    }*/

    /*public function init()
    {
        //echo '<pre>';print_r(1);exit;
    }*/

    /**
     * Renders the index view for the module
     * @return string
     * $city_name например Москва
     * $this->cities_name например moscow
     * $id города
     */
    public function actionIndex($id = null, $city_name = null)
    {
       //echo Url::to(['/artlist/default/index', 'city_name' => 'moscow']);exit;

        //echo Url::to(["/artlist/default/user-city"]);exit;
        if (!empty($city_name)) {
            $gorod = City::find()->where(['url' => $city_name])->one();
            $cityHelper = new \app\components\City();
            $cityHelper->set($gorod->id, $redirect = false);

            $a = $cityHelper->getCity();
            //echo '<pre>';print_r($a);exit;
        }


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

        $this->view->params['city_url'] = $city_name = ($city_name) ? $city_name : 'moscow';


        $gorod = City::find()->where(['url' => $city_name])->one();
        $gid = $gorod->id;
        $this->view->params['city_name'] = $gorod->name;

        $active_blocks = Blocks::find()->where(['city_id' => $gorod->id])->one();


        $isactive = Type::find()->where(['city_id' => $gorod->id, 'name' => 'Фотограф', 'status' => Type::STATUS_ACTIVE])->exists();
        $a = microtime(true);
        $db = Yii::$app->db;

        $most_popular_fotographer = UserType::getDb()->cache(function ($db) use ($isactive, $gid) {
            $q = UserType::find()
                ->select('user_type.*, user.name as un, user.second_name as sn')
                ->where(['user_type.city_id' => $gid])
                ->andWhere(['user_type.type_id' => 1])
                ->andWhere(['IN', 'user.status', [1, 2]])
                ->andWhere(['not', ['user_type.avatar' => '']])
                ->orderby(['user_type.pro_status' => SORT_DESC, 'user_type.raiting' => SORT_DESC])
                ->leftJoin('user', 'user_type.user_id = {{user}}.id')
                ->andWhere(['=', '(select count(*) from admin_rules where admin_rules.user_id = {{user}}.id)', 0])
                // ->andWhere(['>', '(select count(*) from user_media where user_media.user_type_id = user_type.id)', 1])
                ->andWhere(['or',
                    ['>', '(select count(*)
                                  from genre 
                                       left join user_media
                                            on user_media.genre_id = genre.id
                                  where user_media.user_type_id = user_type.id
                                       and (SELECT COUNT(*) from user_media AS um where um.genre_id = user_media.genre_id and um.user_type_id = user_media.user_type_id) > 4)', 1],
                    ['>', '(select count(*)
                                  from user_genre 
                                        left join user_media
                                              on user_media.user_genre_id = user_genre.id
                                  where user_media.user_type_id = user_type.id
                                        and (SELECT COUNT(*) from user_media AS um where um.user_genre_id = user_media.user_genre_id and um.user_type_id = user_media.user_type_id) > 4)', 1]])
                ->limit(8);

            if ($isactive) {
                $q->orderby(['user_type.pro_status' => SORT_DESC, 'user_type.raiting' => SORT_DESC]);
            } else {
                $q->orderby(['user_type.raiting' => SORT_DESC]);
            }

            return $q->all();
        }, 100);

        $lable_b_f  = 0;
        if (count($most_popular_fotographer) < 8) {
            $most_popular_fotographer_2 = UserType::getDb()->cache(function ($db) use ($isactive, $most_popular_fotographer) {
                $q = UserType::find()
                    ->select('user_type.*, user.name as un, user.second_name as sn')
                    ->where(['user_type.type_id' => 1])
                    ->andWhere(['IN', 'user.status', [1, 2]])
                    ->andWhere(['not', ['user_type.avatar' => '']])
                    ->orderBy(new Expression('rand()'))
                    ->leftJoin('user', 'user_type.user_id = {{user}}.id')
                    ->andWhere(['=', '(select count(*) from admin_rules where admin_rules.user_id = {{user}}.id)', 0])
                    ->andWhere(['or',
                        ['>', '(select count(*)
                                  from genre 
                                       left join user_media
                                            on user_media.genre_id = genre.id
                                  where user_media.user_type_id = user_type.id
                                       and (SELECT COUNT(*) from user_media AS um where um.genre_id = user_media.genre_id and um.user_type_id = user_media.user_type_id) > 4)', 1],
                        ['>', '(select count(*)
                                  from user_genre 
                                        left join user_media
                                              on user_media.user_genre_id = user_genre.id
                                  where user_media.user_type_id = user_type.id
                                        and (SELECT COUNT(*) from user_media AS um where um.user_genre_id = user_media.user_genre_id and um.user_type_id = user_media.user_type_id) > 4)', 1]])
                    ->limit(8 - count($most_popular_fotographer));

                if ($isactive) {
                    //   $q->orderby(['user_type.pro_status' => SORT_DESC, 'user_type.raiting' => SORT_DESC]);
                } else {
                    //   $q ->orderby(['user_type.raiting' => SORT_DESC]);
                }

                return $q->all();
            }, 1);
            $lable_b_f = count($most_popular_fotographer) + 1;
            $most_popular_fotographer = array_merge($most_popular_fotographer, $most_popular_fotographer_2);
        }
//echo '<pre>';print_r($gorod);exit;
        //genreblock-----------------------
        $foto_genres = Genre::getDb()->cache(function ($db) use($gid) {
            return Genre::find()
                ->select('genre.*, (select count(distinct user_type_id) 
            from user_media um 
                left join user_type 
                    on user_type.id = um.user_type_id 
                    LEFT JOIN user 
                              ON user_type.user_id = user.id 
                WHERE um.genre_id=genre.id 
                and user_type.avatar <> ""
                and (SELECT COUNT(*) from user_media um1 where um1.genre_id = genre.id and um1.user_type_id = um.user_type_id) >= 5
                 and (select count(*) from admin_rules where admin_rules.user_id = user.id) < 1
                and user_type.city_id = ' . $gid . ') as userUnique')
                ->where(['type' => 1])
                //->andWhere(['>', '(SELECT COUNT(*) from user_media where user_media.user_genre_id = user_genre.id and user_media.user_type_id = user_genre.user_type_id and user_media.is_cover = 0)', 4])
                ->orderby(['userUnique' => SORT_DESC])
                ->all();
        }, 60 * 60 * 1);
        //----------------------
        $rand_genre_id = Yii::$app->params['genres'][mt_rand(0, count(Yii::$app->params['genres']) - 1)];

        $rand_genre = Genre::findOne($rand_genre_id);

        $a = microtime(true);
        $db = Yii::$app->db;

        $fotos_rand_genre = UserMedia::getDb()->cache(function ($db) use($rand_genre_id, $gorod) {
            $fotos_rand_genre_query = UserMedia::find()->with('user', 'user.city')
                ->leftJoin('user_type', 'user_media.user_type_id = user_type.id')
                ->leftJoin('user', 'user_type.user_id = user.id')
                ->leftJoin('city', 'user_type.city_id = city.id')
                ->where(['genre_id' => $rand_genre_id])
                ->andWhere(['IN', 'user.status', [1, 2]])
                ->orderBy(new Expression('rand()'))
                ->limit(27);

            if ($gorod->show_only_photos) {
                $fotos_rand_genre_query->andWhere(['=', 'city.id', $gorod->id]);
            }

            return $fotos_rand_genre_query->all();
        });



        return $this->render('index', [
            'gorod' => $gorod,
            'active_blocks' => $active_blocks,
            'most_popular_fotographer' => $most_popular_fotographer,
            'lable_b_f' => $lable_b_f,
            'foto_genres' => $foto_genres,
            'rand_genre' => $rand_genre,
            'fotos_rand_genre' => $fotos_rand_genre,
        ]);
    }

    /**
     * @return string
     */
    public function actionFotoblockMore($cityid)
    {
        $rand_genre_id = Yii::$app->params['genres'][mt_rand(0, count(Yii::$app->params['genres']) - 1)];

        $rand_genre = Genre::findOne($rand_genre_id);

        $city_name = City::find()->where(['id' => $cityid])->one();

        $fotos_rand_genre_query = UserMedia::find()
            ->select('user_media.*, user.name as un, user.second_name as sn, city.name as cname, city.id as cid')
            ->leftJoin('user_type', 'user_media.user_type_id = user_type.id')
            ->leftJoin('user', 'user_type.user_id = user.id')
            ->leftJoin('city', 'user_type.city_id = city.id')
            ->where(['genre_id' => $rand_genre_id])
            ->andWhere(['IN', 'user.status', [1, 2]])
            ->orderBy(new Expression('rand()'))
            ->limit(27);

        if ($city_name->show_only_photos) {
            $fotos_rand_genre_query->andWhere(['=', 'city.id', $cityid]);
        }

        $fotos_rand_genre = $fotos_rand_genre_query->all();

        return $this->renderPartial('blocks/fotoblock', [
            'fotos_rand_genre' => $fotos_rand_genre,
            'rand_genre' => $rand_genre,
        ]);
    }

    /**
     * all fotos page
     *
     * @param int $id
     * @return string
     * $id - genre_id
     */
    public function actionAllPhotos($id = 0)
    {
        ini_set('memory_limit', '500M');

        $cityHelper = new \app\components\City();
        $a = $cityHelper->getCity();
        $city_name = City::find()->where(['name' => $a['name_ru']])->one();

        $this->view->params['city_names'] = $city_name['name'];// русское название города
        $foto_genres = Genre::find()->where(['type' => 1])->orderby(['sort' => SORT_ASC])->all();

        $cache = Yii::$app->cache;
        $key = 'allPhotos' . $id;
        $res = $cache->get($key);

        if ($res === false) {
            $andWhere = ($id == 0) ? '' : 'and (`user_media`.`genre_id`=' . $id . ')';

            $res = Yii::$app->dbart->createCommand("
                SELECT `user_media`.id FROM `user_media`
                WHERE (`user_media`.`type`=1) $andWhere
            ")->queryAll();

            $cache->set($key, $res, 7200);
        }

        $in = [];
        $i = 0;
        while($i < 55){
            $n = rand(0, (count($res) - 1));
            $idn = $res[$n]['id'];

            if(!isset($in[$idn])){
                $in[] = $idn;
                $i++;
            }
        }

        $fotos_rand_genre = UserMedia::find()
            ->select('{{user_media}}.name, {{user_media}}.user_type_id, {{user}}.name as un, {{user}}.second_name as sn, {{city}}.name as city_name, {{city}}.id as cid, {{country}}.name as country_name')
            ->innerJoin('{{user_type}}', '{{user_media}}.user_type_id = {{user_type}}.id')
            ->leftJoin('{{user}}', '{{user_type}}.id = {{user}}.id')
            ->leftJoin('{{city}}', '{{user_type}}.city_id = {{city}}.id')
            ->leftJoin('{{country}}', '{{city}}.country_id = {{country}}.id')
            ->where(['in', '{{user_media}}.id', $in])
            ->limit(50);

        if (!empty($id)) {
            $fotos_rand_genre->andWhere(['{{user_media}}.genre_id' => $id]);
        }

        $fotos_rand_genre = $fotos_rand_genre->all();

        shuffle($fotos_rand_genre);
        $this->view->params['city_url'] = $city_name->url;
        $this->view->params['city_name'] = $city_name->name;

        return $this->render('all-fotos', [
            'id' => $id,
            'foto_genres' => $foto_genres,
            'fotos_rand_genre' => $fotos_rand_genre,
            'city_name' => $city_name,
            'cities_name' => $city_name->url,
            'city_iden' => $city_name->id,
            'arr' => [],
        ]);
    }

    public function actionAllPhotosMore($city = null, $id = null, $idg = 0, $arr = '')
    {
        ini_set('memory_limit', '500M');
        $arr = json_decode($arr);

        $cityHelper = new \app\components\City();
        $a = $cityHelper->getCity();
        $city_name = City::find()->where(['name' => $a['name_ru']])->one();
        //--------------------------------------
        $cache = Yii::$app->cache;
        $key = 'allPhotos' . $id;
        $res = $cache->get($key);

        if ($res === false) {
            $andWhere = ($id == 0) ? '' : 'and (`user_media`.`genre_id`=' . $id . ')';

            $res = Yii::$app->db->createCommand("
                SELECT `user_media`.id FROM `user_media`
                WHERE (`user_media`.`type`=1) $andWhere
            ")->queryAll();

            $cache->set($key, $res, 7200);
        }

        $in1 = $arr;
        $in = [];

        $i = 0;
        while($i < 55){
            $n = rand(0, (count($res) - 1));
            $idn = $res[$n]['id'];

            if(!isset($in1[$idn])){
                $in[] = $idn;
                $i++;
            }
        }

        //----------------------------------------------
        $fotos_rand_genre = UserMedia::find()
            ->select('{{user_media}}.name, {{user_media}}.user_type_id, {{user}}.name as un, {{user}}.second_name as sn, {{city}}.name as city_name, {{city}}.id as cid, {{country}}.name as country_name')
            ->innerJoin('{{user_type}}', '{{user_media}}.user_type_id = {{user_type}}.id')
            ->leftJoin('{{user}}', '{{user_type}}.id = {{user}}.id')
            ->leftJoin('{{city}}', '{{user_type}}.city_id = {{city}}.id')
            ->leftJoin('{{country}}', '{{city}}.country_id = {{country}}.id')
            ->where(['in', '{{user_media}}.id', $in])
            ->limit(50);

        if (!empty($id)) {
            $fotos_rand_genre->andWhere(['{{user_media}}.genre_id' => $id]);
        }

        $fotos_rand_genre = $fotos_rand_genre->all();

        $total = count($res);
        shuffle($fotos_rand_genre);

        return $this->renderPartial('all-fotos-block', [
            'fotos_rand_genre' => $fotos_rand_genre,
            'city_name' => $city_name,
            'arr' => $arr,
            'total' => $total,
        ]);
    }
}
