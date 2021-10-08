<?php

namespace app\modules\artlist\models\user;

use app\modules\artlist\models\interfaces\Likeable;
use app\modules\artlist\models\Likes;
use app\models\Genre;
use app\modules\artlist\models\DbModel;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use Intervention\Image\ImageManager;
use Vimeo\Vimeo;

/**
 * This is the model class for table "user_media".
 *
 * @property int $id
 * @property string $name
 * @property int $user_type_id
 * @property int $genre_id
 * @property int $user_genre_id
 * @property int $type
 * @property int $is_cover
 * @property string $created_date
 * @property int $like_media
 * @property int $sort
 * @property string $preview
 *
 * @property Likes[] $likes
 * @property UserType $userType
 * @property UserType $user
 * @property UserGenre $userGenre
 * @property Genre $genre
 * @property UserReview[] $userReviews
 */
class UserMedia extends DbModel implements Likeable
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_media';
    }

    public function deleteRecursive($relations = [])
    {
        if($relations == [])
            $relations = ["userReviews"];

        parent::deleteRecursive($relations);
    }

    const TYPE_PHOTO = 1;
    const TYPE_VIDEO = 2;

    public $breed = 'media';

    private static $_coverArr = [];

    public $un;
    public $sn;
    public $g_name;
    public $count_rev;
    public $avatar;
    public $pro;
    public $cname;
    public $cid;
    public $tname;
    public $countGenre;
    public $city_name;
    public $country_name;
    public $u_t_id;

    public function beforeSave($insert)
    {
        $this->created_date = date("Y-m-d H:i");
        return parent::beforeSave($insert);
    }
	
	public function afterSave($insert, $changedAttributes)
	{
		if ($insert) {
			if ($this->genre_id && $this->user->type == UserType::TYPE_PHOTOGRAPHER) {
				$cost = UserGenreCost::find()->where([
					'user_type_id' => $this->user_type_id,
					'genre_id' => $this->genre_id
				])->one();
				if (!$cost) {
					$cost = new UserGenreCost();
					$cost->user_type_id = $this->user_type_id;
					$cost->genre_id = $this->genre_id;
					$cost->hour_cost = UserType::MIN_HOUR_COST;
					$cost->day_cost = UserType::MIN_HOUR_COST;
					$cost->save();
				} else if ($cost->hour_cost<UserType::MIN_HOUR_COST) {
					$cost->hour_cost = UserType::MIN_HOUR_COST;
					$cost->save();
				}
			}
		}
		parent::afterSave($insert, $changedAttributes);	
	}

    public function afterDelete()
    {
        // Проверяем чтобы кол-во фотографий в альбоме основного жанра было не меньше минимального
        $userType = $this->user;
        if ($this->genre_id == $userType->main_genre_id) {
            $countForMain = UserMedia::find()->where([
                'user_type_id' => $this->user_type_id,
                'type' => UserMedia::TYPE_PHOTO,
                'genre_id' => $this->genre_id
            ])->count();
            if ($countForMain < Yii::$app->params['minPhotosForActive']) {
                $userType->updateAttributes(['main_genre_id' => null]);
            }
        }
        parent::afterDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['name', 'user_type_id', 'type', 'created_date', 'like_media', 'sort'], 'required'],
            [['user_type_id', 'genre_id', 'user_genre_id', 'type', 'like_media', 'sort', 'is_cover'], 'integer'],
            [['created_date', 'g_name', 'count_rev', 'avatar', 'cname', 'tname'], 'safe'],
            [['name'], 'string', 'max' => 190],
            [['user_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserType::className(), 'targetAttribute' => ['user_type_id' => 'id']],
            [['user_genre_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserGenre::className(), 'targetAttribute' => ['user_genre_id' => 'id']],
            [['genre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genre::className(), 'targetAttribute' => ['genre_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'user_type_id' => 'User Type ID',
            'genre_id' => 'Genre ID',
            'user_genre_id' => 'User Genre ID',
            'type' => 'Type',
            'created_date' => 'Created Date',
            'like_media' => 'Like Media',
            'sort' => 'Sort',
			'total_views' => 'Total Views'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Likes::className(), ['user_media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserType()
    {
        return $this->hasOne(UserType::className(), ['type_id' => 'user_type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGenre()
    {
        //return 'asd';
        return $this->hasOne(UserGenre::className(), ['id' => 'user_genre_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenre()
    {
        return $this->hasOne(Genre::className(), ['id' => 'genre_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserReviews()
    {
        return $this->hasMany(UserReview::className(), ['user_media_id' => 'id']);
    }

    public static function getCountPhotoUserMedia()
    {
        $ut =  UserType::getUserType();
        $resG = self::find()->select('genre_id, 
        (SELECT COUNT(id) FROM user_media as um WHERE um.genre_id=user_media.genre_id AND um.user_type_id='.$ut->id.' AND is_cover = 0 AND genre_id>0) as c')
            ->where(['user_type_id' => $ut->id])->andWhere(['>','genre_id',0])->asArray()->groupBy('genre_id')->all();
        $resGU = self::find()->select('user_genre_id,
        (SELECT COUNT(id) FROM user_media as um WHERE  um.user_type_id='.$ut->id.' AND is_cover = 0 AND user_genre_id>0) as c')
            ->where(['user_type_id' => $ut->id])->andWhere(['>','user_genre_id',0])->asArray()->groupBy('user_genre_id')->all();

        $r = [false, false];
        if ($resG) {
            $r[0] = ArrayHelper::index($resG, 'genre_id', 'genre_id');
        }
        if ($resGU) {

            $r[1] = ArrayHelper::index($resGU, 'user_genre_id', 'user_genre_id');
        }
        return $r;

    }

    public static function checkMinPhoto()
    {

        $userType = \app\models\user\UserType::getUserType();
        if (!empty($userType)) {
            $userMedia = self::getCountPhotoUserMedia();

            if (!empty($userMedia[0])) {
                foreach ($userMedia[0] as $k => $media) {
                    if ($media[$k]['c'] < 5) {
                        $um = UserMedia::find()->where(['genre_id' => $k, 'user_type_id' => $userType->id])->all();
                        if ($um) {
                            foreach ($um as $u) {
                                if (is_file("../uploads/user/{$userType->user_id}/{$u->name}")) {
                                    unlink("../uploads/user/{$userType->user_id}/{$u->name}");
                                }
                                if (!empty($u->userReviews)) {
                                    $u->userReviews->deleteAll();
                                }
                                $u->delete();
                            }
                        }
                    }
                }

            }
            if (empty($userMedia[1])) {
               UserGenre::deleteAll(['user_type_id'=>$userType->id]);
            }else{
                $arU= [];
                $arNU= [];
                foreach ($userMedia[1] as $k => $media) {
                    if($media[$k]['c']>5){
                        $arU[]=$media[$k]['user_genre_id'];
                    }else{
                        $arNU[]=$media[$k]['user_genre_id'];
                    }
                    if(!empty($arNU)){
                        $arNU = implode(',',$arNU);
                        UserMedia::deleteAll('user_genre_id IN ('.$arNU.')');
                    }
                    if(!empty($arU)){
                        $arU = implode(',', $arU);
                        UserGenre::deleteAll('id NOT IN ('.$arU.') AND user_type_id='.$userType->id);
                    }


                }
            }


        }


    }



    public static function getCover($ut = [], $all = true)
    {
        if (empty(self::$_coverArr)) {
            self::$_coverArr = ['g' => [], 'ug' => [], 'v' => []];
            if (empty($ut)) {
                $ut = \app\models\user\UserType::getUserType();
            }
            $getDefG = UserMedia::find()->select('genre_id, genre.name as cname, user_media.type')
                ->where(['user_type_id' => $ut->id])
                ->andWhere(['>', 'user_media.genre_id', 0])
                ->andWhere(['user_media.type'=>1])
                ->joinWith(['genre'])
                ->groupBy('user_media.genre_id')
                ->asArray()
                ->all();
            $getDefGU = UserMedia::find()->select('user_genre_id, user_genre.name  as cname,')
                ->where(['user_media.user_type_id' => $ut->id])
                ->andWhere(['>', 'user_media.user_genre_id', 0])
                ->joinWith(['userGenre'])
                ->groupBy('user_media.user_genre_id')
                ->asArray()
                ->all();

            if (!empty($getDefG)) {
                foreach ($getDefG as $value) {
                        self::$_coverArr['g'][$value['genre_id']]['n'] = $value['cname'];
                        $r = UserMedia::find()
                            ->select('name, (SELECT COUNT(*) FROM user_media AS um WHERE  um.user_type_id=' . $ut->id . ' AND um.genre_id='.$value['genre_id'].') as countGenre')
                            ->where(['genre_id' => $value['genre_id']])
                            ->andWhere(['user_type_id' => $ut->id])
                            ->orderBy(['sort' => SORT_ASC])
                            ->limit(10)
                            ->asArray()
                            ->all();
                        $m = count($r) - 1;
                        $aId = [];
                        $i = 0;
                        do {
                            if ($ut->pro_status == 1 || $m < 5) {
                                $ra = $i;
                            } else {
                                $ra = $i;
                            }
                            if(empty($r[$ra])) break;
                            if (array_search($ra, $aId) === false || $m < 5) {
                                self::$_coverArr['g'][$value['genre_id']]['v'][] = $r[$ra];
                                $i++;
                            }
                        } while ($i < 5);
                }
            }
            if (!empty($getDefGU)) {
              //  var_dump($getDefGU);
                foreach ($getDefGU as $value) {
                    self::$_coverArr['ug'][$value['user_genre_id']]['n'] = $value['cname'];
                    $r = UserMedia::find()->select('user_media.name, user_genre.name as g_name, (SELECT COUNT(*) FROM user_media AS um WHERE  um.user_type_id=' . $ut->id . ' AND um.user_genre_id='.$value['user_genre_id'].') as countGenre')
                        ->where(['user_genre_id' => $value['user_genre_id']])->andWhere(['user_media.user_type_id' => $ut->id])
                        ->leftJoin('user_genre', 'user_genre.id=user_media.user_genre_id')
                        ->orderBy(['user_media.is_cover' => SORT_DESC, 'user_media.sort' => SORT_DESC])->asArray()->all();
                    $m = count($r) - 1;
                    $aId = [];
                    $i = 0;
                    do {
                        if ($ut->pro_status == 1 || $m < 5) {
                            $ra = $i;
                        } else {
                            $ra = $i;
                        }
                        if(empty($r[$ra])) break;
                        if (array_search($ra, $aId) === false || $m < 5) {
                            self::$_coverArr['ug'][$value['user_genre_id']]['v'][] = $r[$ra];
                            $i++;
                        }
                    } while ($i < 5);
                }
            }
            $r = UserMedia::find()->select('name, id')
                ->where(['user_type_id' => $ut->id])->andWhere(['type'=>2])->asArray()->all();
            self::$_coverArr['v'] = $r;
        }

        return self::$_coverArr;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function createPreview()
    {
        $datetime = new DateTime($this->created_date);

        $year  = $datetime->format('Y');
        $month = $datetime->format('m');
        $day   = $datetime->format('d');

        if($this->preview && file_exists("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return 'http://artlist/images/photos/n/p/'.$year.'/'.$month.'-'.$day.'/'.$this->user->city_id.'/'.$this->user->id.'/'.$this->preview;
        }
        elseif($this->preview && file_exists("/var/www/www-root/data/www/artlist.pro/web/images/photos/o/p/".$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return 'http://artlist/images/photos/o/p/'.$this->user->city_id.'/'.$this->user->id.'/'.$this->preview;
        }
        elseif($this->preview && file_exists($_SERVER['DOCUMENT_ROOT'] ."/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return 'https://'.$_SERVER['HTTP_HOST'].'/images/photos/n/p/'.$year.'/'.$month.'-'.$day.'/'.$this->user->city_id.'/'.$this->user->id.'/'.$this->preview;
        }
        elseif($this->preview && file_exists($_SERVER['DOCUMENT_ROOT'] ."/images/photos/o/p/".$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return 'https://'.$_SERVER['HTTP_HOST'].'/images/photos/o/p/'.$this->user->city_id.'/'.$this->user->id.'/'.$this->preview;
        }
        else{
            return 'https://'.$_SERVER['HTTP_HOST'].'/images/_system/video_null.png';
        }
    }
	
    public function createPreviewMin()
    {
        $datetime = new DateTime($this->created_date);

        $year  = $datetime->format('Y');
        $month = $datetime->format('m');
        $day   = $datetime->format('d');
		$image = "/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview;
        if($this->preview && file_exists("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return "http://artlist/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview;
        }
        elseif($this->preview && file_exists("/var/www/www-root/data/www/artlist.pro/web/images/photos/o/p_lazy/".$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return 'http://artlist/images/photos/o/p_lazy/'.$this->user->city_id.'/'.$this->user->id.'/'.$this->preview;
        }
        elseif($this->preview && file_exists($_SERVER['DOCUMENT_ROOT'] ."/images/photos/o/p_lazy/".$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return 'https://'.$_SERVER['HTTP_HOST'].'/images/photos/o/p_lazy/'.$this->user->city_id.'/'.$this->user->id.'/'.$this->preview;
        }
        elseif($this->preview && file_exists($_SERVER['DOCUMENT_ROOT'] ."/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview)){
            return "https://".$_SERVER['HTTP_HOST']."/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview;
        }
        elseif($this->preview && file_exists($image))
		{
						if(!is_dir("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id))
						{
						BaseFileHelper::createDirectory("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id, 0755);
						}
						
						$manager = new ImageManager(array('driver' => 'gd'));
                        $imageSize = $manager->make($image);
                        $imageSize->resize(50,null,  function($img) {
                            $img->aspectRatio();
                        });
                        $imageSize->save("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview);
					
					return "http://artlist/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$this->user->city_id."/".$this->user->id."/".$this->preview;
        }
        else{
            return 'https://'.$_SERVER['HTTP_HOST'].'/images/_system/video_null.png';
        }
    }

    public function like()
    {
        $this->updateCounters(['like_media' => 1]);
    }

    public function dislike()
    {
        $this->updateCounters(['like_media' => -1]);
    }

    public function getLikeId()
    {
        /** @var Likes $like */
        $like = Likes::find()->where(['user_media_id' => $this->id, 'user_type_id' => UserType::getUserType()->id])->one();

        return $like ? $like->id : 0;
    }

    public function getNext()
    {
        $getNextid = UserMedia::find()->where([
            'genre_id' => $this->genre_id,
            'user_genre_id' => $this->user_genre_id,
            'type' => $this->type,
            'user_type_id' => $this->user_type_id
        ])
            ->andWhere(['>', 'sort', $this->sort])
            ->orderBy('sort asc')
            ->one();
			
		if (!$this->sort) {
			$getNextid = UserMedia::find()->where([
            'genre_id' => $this->genre_id,
            'user_genre_id' => $this->user_genre_id,
            'type' => $this->type,
            'user_type_id' => $this->user_type_id
        ])
            ->andWhere(['>', 'id', $this->id])
            ->orderBy('id asc')
            ->one();
		}
		
		return $getNextid;
    }

    public function getPrev()
    {
        $getPrevid = UserMedia::find()->where([
            'genre_id' => $this->genre_id,
            'user_genre_id' => $this->user_genre_id,
            'type' => $this->type,
            'user_type_id' => $this->user_type_id
        ])
            ->andWhere(['<', 'sort', $this->sort])
            ->orderBy('sort desc')
            ->one();
			
		if (!$this->sort) {
			$getPrevid = UserMedia::find()->where([
            'genre_id' => $this->genre_id,
            'user_genre_id' => $this->user_genre_id,
            'type' => $this->type,
            'user_type_id' => $this->user_type_id
        ])
            ->andWhere(['<', 'id', $this->id])
            ->orderBy('id desc')
            ->one();
		}
		
		return $getPrevid;
    }
	
	public function get_title($url){
			$auth = base64_encode('BMFTb2:7tbjaF');

			$aContext = array(
				'http' => array(
					'proxy' => 'tcp://91.188.243.88:9621',
					'request_fulluri' => true,
					'header' => "Proxy-Authorization: Basic $auth",
				),
			);
			$cxContext = stream_context_create($aContext);
			
	  $str = @file_get_contents($url, False, $cxContext);
	  if(strlen($str)>0){
		$title = stripos($str,"Приносим извинения");  
		$sorry = stripos($str,"Sorry"); 
		if ($title)
			return "title";
		elseif ($sorry)
			return "sorry";
		else
			echo "\r\n naideno!!!!!!!!!!\r\n";
			return 0;
	  } else
			return "empty";
	}
	
	public function findImages() {
		$st = $this->preview;
		$userType = UserType::find()->where(['id' => $this->user_type_id])->one();
		
		$datetime = new DateTime($this->created_date);
		$date = new DateTime('-3 days');

        $year = $datetime->format('Y');
        $month = $datetime->format('m');
        $day = $datetime->format('d');
		
		if (($this->id < 680556) && ($date > $datetime))
		$image = "/var/www/www-root/data/www/artlist.pro/web/images/photos/o/p/".$userType->city_id."/".$this->user_type_id."/".$st;
			else
		$image = "/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st;
		
		if(!file_exists($image)) {
			echo $this->id." | ".$image." | не существует \r\n";			
		}
	}
	
	public function createVideoPreview()
	{
		$l = $this->name;
		$userType = UserType::find()->where(['id' => $this->user_type_id])->one();
		
		/*Yii::$app->response->format = Response::FORMAT_JSON;

        $userType = UserType::getUserType();*/
        $datetime = new DateTime();

        $year = $datetime->format('Y');
        $month = $datetime->format('m');
        $day = $datetime->format('d');
		$hour = $datetime->format('H');
        $min = $datetime->format('i');
        $sec = $datetime->format('s');
		$directory = Yii::getAlias("@app")."/web";
		
		 if(@get_headers($l, 1)[0] == 'HTTP/1.1 404 Not Found')
            return ['id' => 0, 'msg' => 'Некорректная ссылка!'];
		
		/*
        if($userType->isPro()){
            if($userType->type_id == UserType::TYPE_PHOTOGRAPHER && (count($userType->videos) > 4))
                return ['id' => 0, 'msg' => 'Достигнут предел, Вы не можете добавить видео.'];
            if($userType->type_id == UserType::TYPE_VIDEOGRAPHER && (count($userType->videos) > 19))
                return ['id' => 0, 'msg' => 'Достигнут предел, Вы не можете добавить видео.'];
            if(in_array($userType->type_id, [UserType::TYPE_STYLIST, UserType::TYPE_MODEL, UserType::TYPE_STUDIO]) && count($userType->videos) > 4)
                return ['id' => 0, 'msg' => 'Достигнут предел, Вы не можете добавить видео.'];
        }
        else{
            if($userType->type_id == UserType::TYPE_PHOTOGRAPHER && (count($userType->videos) > 0))
                return ['id' => 0, 'msg' => 'Достигнут предел, Вы не можете добавить видео.'];
            if($userType->type_id == UserType::TYPE_VIDEOGRAPHER && (count($userType->videos) > 2))
                return ['id' => 0, 'msg' => 'Достигнут предел, Вы не можете добавить видео.'];
            if(in_array($userType->type_id, [UserType::TYPE_STYLIST, UserType::TYPE_MODEL, UserType::TYPE_STUDIO]) && count($userType->videos) > 4)
                return ['id' => 0, 'msg' => 'Достигнут предел, Вы не можете добавить видео.'];
        } */

        if(stristr($l, 'iframe src') || stristr($l, 'vimeo.com') || stristr($l, 'youtu.be') || stristr($l, 'youtube.com') || stristr($l, 'vk.com')){

            if(!is_dir($directory . "/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$userType->city_id.'/'.$userType->id)){
                BaseFileHelper::createDirectory($directory . "/images/photos/n/p/".$year."/".$month.'-'.$day.'/'.$userType->city_id.'/'.$userType->id, 0755);
                BaseFileHelper::createDirectory($directory . "/images/photos/n/p_lazy/".$year."/".$month.'-'.$day.'/'.$userType->city_id.'/'.$userType->id, 0755);
            }
			
			sleep (5);

            if(stristr($l, 'iframe src')){
                $st = explode('"', $l);
                $l = $st[1];
            } else {
                if(stristr($l, 'www.youtube.com/w')){
                    $st = explode('watch?v=', $l);
                    $st = $st[1];
						if (stristr($st, '&')){
							 $linkay = explode('&', $st);
							 $st = $linkay[0];
						}

                    $l = 'https://www.youtube.com/embed/'.$st;
                    copy("http://i2.ytimg.com/vi/" . $st . "/0.jpg",$directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");

                    $image = $directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
					$imagelazy = $directory."/images/photos/n/p_lazy/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";

                    if(file_exists($image))
                    {
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $imageSize = $manager->make($image);
                        $w=$imageSize->getWidth();
                        $h=$imageSize->getHeight();
                        $x1=0;
                        $y1=0;
                        if($w>$h){
                            $x1= round(($w-$h)/2);
                            $w=$h;
                        }else{
                            $y1=round(($h-$w) / 2);
                            $h=$w;
                        }
                        $imageSize->crop($w,$h, $x1, $y1);
                        $imageSize->resize(308,null,  function($img) {
                            $img->aspectRatio();
                        });
                        $imageSize->save($directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
						
						$imageSize->resize(50,null,  function($img) {
							$img->aspectRatio();
						});
						$imageSize->save($imagelazy);
                    }
                }
                if(stristr($l, 'youtu.be')){
                    $st = explode('youtu.be/', $l);
                    $st = $st[1];
						if (stristr($st, '&')){
							 $linkay = explode('&', $st);
							 $st = $linkay[0];
						}
					$l = 'https://www.youtube.com/embed/'.$st;
                    copy("http://i2.ytimg.com/vi/" . $st . "/0.jpg",$directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");

                    $image = $directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
					$imagelazy = $directory."/images/photos/n/p_lazy/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";

                    if(file_exists($image))
                    {
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $imageSize = $manager->make($image);
                        $w=$imageSize->getWidth();
                        $h=$imageSize->getHeight();
                        $x1=0;
                        $y1=0;
                        if($w>$h){
                            $x1= round(($w-$h)/2);
                            $w=$h;
                        }else{
                            $y1=round(($h-$w) / 2);
                            $h=$w;
                        }
                        $imageSize->crop($w,$h, $x1, $y1);
                        $imageSize->resize(308,null,  function($img) {
                            $img->aspectRatio();
                        });
                        $imageSize->save($directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
						
						$imageSize->resize(50,null,  function($img) {
							$img->aspectRatio();
						});
						$imageSize->save($imagelazy);
                    }
					
                }
                if(stristr($l, 'youtube.com/embed')){
                    $st = explode('youtube.com/embed/', $l);
                    $st = $st[1];
						if (stristr($st, '/?')){
							 $linkay = explode('/?', $st);
							 $st = $linkay[0];
						} else if (stristr($st, '?')){
							 $linkay = explode('?', $st);
							 $st = $linkay[0];
						} else if (stristr($st, '&')){
							 $linkay = explode('&', $st);
							 $st = $linkay[0];
						}
					$l = 'https://www.youtube.com/embed/'.$st;
					$img_video = "http://i2.ytimg.com/vi/" . $st . "/0.jpg";
					$er404=@get_headers($img_video, 1)[0];
					if($er404 != 'HTTP/1.0 404 Not Found')
                    copy("http://i2.ytimg.com/vi/" . $st . "/0.jpg",$directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
                    $image = $directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
					$imagelazy = $directory."/images/photos/n/p_lazy/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";

                    if(file_exists($image))
                    {
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $imageSize = $manager->make($image);
                        $w=$imageSize->getWidth();
                        $h=$imageSize->getHeight();
                        $x1=0;
                        $y1=0;
                        if($w>$h){
                            $x1= round(($w-$h)/2);
                            $w=$h;
                        }else{
                            $y1=round(($h-$w) / 2);
                            $h=$w;
                        }
                        $imageSize->crop($w,$h, $x1, $y1);
                        $imageSize->resize(308,null,  function($img) {
                            $img->aspectRatio();
                        });
                        $imageSize->save($directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
						
						$imageSize->resize(50,null,  function($img) {
							$img->aspectRatio();
						});
						$imageSize->save($imagelazy);
                    }
					
                }
                if(stristr($l, 'vk.com')){
					if(stristr($l, 'video_ext.php?oid')){
						$parts = parse_url($l);
						parse_str($parts['query'], $query);
						$oid = $query['oid'];
						$id = $query['id'];
						$st = $oid ."_". $id;
					} else {
						$st = explode('video', $l);
						$st = $st[1];
					}

                    $videoGet = file_get_contents("https://api.vk.com/method/video.get?v=5.92&videos=".$st."&access_token=d48ba11dcc2a5a9b2ebbba73c16ea62671c73ee60f8c849b800aa527be59db82a2d9811b1978ed1cdf9cd");
                    $json_video = json_decode($videoGet,1);
                    $l = $json_video["response"]['items'][0]["player"];
                    $img_video = $json_video["response"]['items'][0]["photo_320"];
                   // var_dump($json_video);die();
				   if ($st != "" && $img_video != "") 
                    copy($img_video,$directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
                    $image = $directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
					$imagelazy = $directory."/images/photos/n/p_lazy/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
                    if(file_exists($image))
                    {
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $imageSize = $manager->make($image);
                        $w=$imageSize->getWidth();
                        $h=$imageSize->getHeight();
                        $x1=0;
                        $y1=0;
                        if($w>$h){
                            $x1= round(($w-$h)/2);
                            $w=$h;
                        }else{
                            $y1=round(($h-$w) / 2);
                            $h=$w;
                        }
                        $imageSize->crop($w,$h, $x1, $y1);
                        $imageSize->resize(256,null,  function($img) {
                            $img->aspectRatio();
                        });
                        $imageSize->save($directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
						
						$imageSize->resize(50,null,  function($img) {
							$img->aspectRatio();
						});
						$imageSize->save($imagelazy);
                    }else{
                        $st = null;
					}
                }
                if(stristr($l, 'vimeo.com')){
					if (stristr($l, 'player.vimeo.com')){
						$st = explode('video/', $l);
						$st = $st[1];
						$st = explode('?', $st);
						$st = $st[0];
						$l = 'https://player.vimeo.com/video/'.$st;
					} else {
						$st = explode('/', $l);
						$st = trim(end($st));
						$l = 'https://player.vimeo.com/video/'.$st;
					}

                    $client = new Vimeo(
                        "a34fe63cb23afc660ebaf8033c0b9d7bcf941f32",
                        "XpNetblqK1gpKepNpK0hLwpUm+mUQM5z97U6yNxTTikatrNDD/z47vy8l+Vr75OuJPLjKvIuV8M9leYkGOdfFG64zk7zj+9y9ZijwouOawG8xXqrg/TJ4J+mdKnrkKng",
                        "342c3aee8d1b15f5e7e7d06af30798cd"
                    );
					
                    $response = $client->request("/videos/$st/pictures", [], 'GET');
                    if ($response && !UserMedia::get_title($l)) {
						
						if (!$response['data']){						
						$pattern = "/(?<=background:\ url\(').*?\.jpg/";
						$str = @file_get_contents($l);
						if($str)
						preg_match($pattern,$str,$img_video);
						$img_video = $img_video[0];
						} else {						
                        $img_video = end($response['body']['data'][0]['sizes'])['link']; }
						
						//var_dump(json_encode($img_video));
						if($img_video)
                        copy($img_video,$directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");

                        $image = $directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
						$imagelazy = $directory."/images/photos/n/p_lazy/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
                        if(file_exists($image))
                        {
                            $manager = new ImageManager(array('driver' => 'gd'));
                            $imageSize = $manager->make($image);
                            $w=$imageSize->getWidth();
                            $h=$imageSize->getHeight();
                            $x1=0;
                            $y1=0;
                            if($w>$h){
                                $x1= round(($w-$h)/2);
                                $w=$h;
                            }else{
                                $y1=round(($h-$w) / 2);
                                $h=$w;
                            }
                            $imageSize->crop($w,$h, $x1, $y1);
                            $imageSize->resize(256,null,  function($img) {
                                $img->aspectRatio();
                            });
                            $imageSize->save($directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
						
						$imageSize->resize(50,null,  function($img) {
							$img->aspectRatio();
						});
						$imageSize->save($imagelazy);
                        }
                    }else{
                        $st = null;
                    }
                } 
				if(stristr($l, 'player.vimeo.com')){
                    $st = explode('video/', $l);
                    $st = $st[1];
                    $st = explode('?', $st);
                    $st = $st[0];
                    $l = 'https://player.vimeo.com/video/'.$st;

                    $client = new Vimeo(
                        "a34fe63cb23afc660ebaf8033c0b9d7bcf941f32",
                        "XpNetblqK1gpKepNpK0hLwpUm+mUQM5z97U6yNxTTikatrNDD/z47vy8l+Vr75OuJPLjKvIuV8M9leYkGOdfFG64zk7zj+9y9ZijwouOawG8xXqrg/TJ4J+mdKnrkKng",
                        "342c3aee8d1b15f5e7e7d06af30798cd"
                    );

                    $response = $client->request("/videos/$st/pictures", [], 'GET');

                    if ($response && !UserMedia::get_title($l)) {
						
						if (!$response['data']){						
						$pattern = "/(?<=background:\ url\(').*?\.jpg/";
						$str = @file_get_contents($l);
						if($str)
						preg_match($pattern,$str,$img_video);
						$img_video = $img_video[0];
						} else {						
                        $img_video = end($response['body']['data'][0]['sizes'])['link']; }

						if($img_video)
                        copy($img_video,$directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");

                        $image = $directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
						$imagelazy = $directory."/images/photos/n/p_lazy/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg";
                        if(file_exists($image))
                        {
                            $manager = new ImageManager(array('driver' => 'gd'));
                            $imageSize = $manager->make($image);
                            $w=$imageSize->getWidth();
                            $h=$imageSize->getHeight();
                            $x1=0;
                            $y1=0;
                            if($w>$h){
                                $x1= round(($w-$h)/2);
                                $w=$h;
                            }else{
                                $y1=round(($h-$w) / 2);
                                $h=$w;
                            }
                            $imageSize->crop($w,$h, $x1, $y1);
                            $imageSize->resize(256,null,  function($img) {
                                $img->aspectRatio();
                            });
                            $imageSize->save($directory."/images/photos/n/p/".$year."/".$month."-".$day."/".$userType->city_id.'/'.$userType->id.'/'.$st.".jpg");
						
							$imageSize->resize(50,null,  function($img) {
								$img->aspectRatio();
							});
							$imageSize->save($imagelazy);
                        }
                    }
					else
					{
                        $st = null;
                    }
                }
            }
			$um = UserMedia::find()->where(['user_type_id' => $userType->id])->andWhere(['type' => 2])->andWhere(['name' => $this->name])->one();
			//$um2 = UserMedia::find()->where(['user_type_id' => $this->user_type_id])->andWhere(['type' => 2])->andWhere(['name' => $this->name])->one();
			//if (UserMedia::get_title($l)) 
			echo "st = ".$st." | usertype_id = ".$this->user_type_id." | id = ".$this->id." | reason = ".UserMedia::get_title($l)."\r\n";
			
			if ($um) {
            $um->preview = ($st) ? $st.".jpg" : UserMedia::get_title($l);
            $um->created_date = $year."-".$month."-".$day." ".$hour.":".$min.":".$sec;
            $um->save();
			}
	}
        return ['id' => 1];
	}
	
	public function updateViewHistory() 
	{
		$isbot = 0;
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
		
		if (empty($user_agent)) {
		 $isbot = 0;
		}
		
		//проверяем на гугл и на яндекс
		if(stristr($user_agent, 'yandex') !== false || stristr($user_agent, 'google') !== false || stristr($user_agent, 'bot') !== false) {
			$isbot = 1;
		}
		//прочие боты
		$bots = [
		'Accoona', 'ia_archiver', 'Ask Jeeves', 'W3C_Validator', 'WebAlta', 'YahooFeedSeeker',
		'Yahoo!', 'Ezooms', 'SiteStatus', 'Nigma.ru', 'Baiduspider', 'SISTRIX', 'findlinks',
		'proximic', 'OpenindexSpider', 'statdom.ru', 'Spider', 'Snoopy', 'heritrix', 'Yeti',
		'DomainVader', 'StackRambler'
		];
		//ищем в массиве
		foreach ($bots as $bot) {
			if (stripos($user_agent, $bot) !== false) {
				$isbot = 1;
			}
		}
		// total_views++
		//$this->updateCounters(['total_views'=>1]);
		if (!$isbot) 
		{
			
			self::findOne($this->id)->updateCounters(['total_views'=>1]);
			$rec = new UserMediaViewHistory();
		$rec->user_media_id = $this->id;
		$rec->media_owner_id = $this->user_type_id;
		$user = UserType::getUserType();
		if ($user) {
			$rec->media_viewer_id = $user->id;
		}
		$rec->ip = Yii::$app->getRequest()->getUserIP();
		$res = $rec->save();
		if ($res && $user) $user->updateRating();
		}
	}
	
}

	