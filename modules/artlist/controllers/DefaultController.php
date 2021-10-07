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
                    //Yii::$app->request->get('ng'),
                ],
                //'dependency' => [
                //'class' => 'yii\caching\DbDependency',
                //'sql' => 'SELECT COUNT(*) FROM post',
                //],
            ],
        ];
    }*/

    /**
     * Renders the index view for the module
     * @return string
     * $city_name например Москва
     * $id города
     */
    public function actionIndex($id = null, $city_name = null)
    {
       //echo Url::to(['/artlist/default/index', 'city_name' => 'moscow']);exit;

        //echo Url::to(["/artlist/default/user-city"]);exit;

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
//echo '<pre>';print_r($city_name);exit;

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

        //echo microtime(true) - $a;
        //echo '<br>';
        //exit;

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
        //echo '<pre>';print_r($foto_genres);exit;


        return $this->render('index', [
            'gorod' => $gorod,
            'active_blocks' => $active_blocks,
            'most_popular_fotographer' => $most_popular_fotographer,
            'lable_b_f' => $lable_b_f,
            'foto_genres' => $foto_genres,
        ]);
    }

    public function actionUserCity($city_name)
    {
        $translit = new Translit();

        $cities_name = $translit->translit($city_name, false, 'en-ru');
        $pos = strpos($cities_name, 'йй');
        if ($pos === false) $cities_name = str_replace('й', 'ь', $cities_name); else $cities_name = str_replace('йй', 'ый', $cities_name);
        $cities_name = str_replace('гх', 'ж', $cities_name);
        $cities_name = str_replace('сцх', 'щ', $cities_name);
        $cities_name = str_replace('__', '-', $cities_name);
        $cities_name = str_replace('_', ' ', $cities_name);
        $city = City::find()->where(['url' => $city_name])->one();

        if (!$city) {
            if (Yii::$app->request->cookies->getValue('city')) {
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'city',
                    'value' => Yii::$app->request->cookies->getValue('city'),
                    'expire' => time() + 60 * 60 * 24 * 90, // 90 дней
                ]));
            }

            return $this->redirect(['site/error']);
        }
        if (!empty(Yii::$app->session->getFlash('returnUrl'))) {

            $redir = Yii::$app->session->getFlash('returnUrl', null, true);
            Yii::$app->session->removeFlash('returnUrl');
            return $this->redirect($redir);

        }
        Yii::$app->session->getFlash('returnUrl', null, true);

        if (!Yii::$app->request->isAjax) {
            return $this->actionIndex($city->id, $city->name);
        } else {
            return json_encode(['city' => $city->name]);
        }
    }
}
