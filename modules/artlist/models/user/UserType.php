<?php

namespace app\modules\artlist\models\user;

use app\components\Url;
use app\models\Bestphotographer;
use app\models\Genre;
use app\models\interfaces\Likeable;
use app\models\Likes;
use app\models\News;
use app\modules\artlist\models\City;
use app\modules\artlist\models\Type;
use app\modules\artlist\models\DbModel;
use DateTime;
use Yii;
use yii\db\Expression;
use yii\helpers\BaseFileHelper;
use Intervention\Image\ImageManager;
use yii\helpers\FileHelper;
use yii\db\Query;

/**
 * This is the model class for table "user_type".
 *
 * @property int $id
 * @property int $user_id
 * @property int $city_id
 * @property string $avatar
 * @property int $raiting
 * @property int $type_id
 * @property int $like_style_id
 * @property string $description
 * @property string $post_address
 * @property string $phone
 * @property string $site_url
 * @property string $soc_vk
 * @property string $soc_vk_group
 * @property string $soc_facebook
 * @property string $soc_ok
 * @property string $soc_instagram
 * @property string $soc_twitter
 * @property string $soc_linkedin
 * @property string $soc_vimeo
 * @property string $skype
 * @property string $created_date
 * @property int $status
 * @property int $pro_status
 * @property string $pro_date
 * @property string $last_visit
 * @property string $name
 * @property string $second_name
 * @property string $categoryName
 * @property int $pro_template
 * @property int $hour_cost
 * @property int $day_cost
 * @property int $show_contacts
 * @property int $show_flash
 * @property int $main_genre_id
 * @property int $show_in_cat
 *
 * @property Bestphotographer[] $bestphotographers
 * @property Likes[] $likes
 * @property News[] $news
 * @property UserCompetition[] $userCompetitions
 * @property UserGenre[] $userGenres
 * @property UserMedia[] $userMedia
 * @property UserNotice[] $userNotices
 * @property UserReview[] $userReviews
 * @property UserReview[] $userReviews0
 * @property UserMedia[] $videos
 * @property City $city
 * @property User $user
 * @property Type $type
 * @property Type $realType
 * @property UserCompetition[] $wins
 * @property string $avatarPath
 * @property Genre $mainGenre
 */
class UserType extends DbModel
{
    /**
     * {@inheritdoc}
     */
	private static $_ratingUpdated = false;
	
	// Для всех кроме моделей
	// Блин, пришлось сделать public ЧАВ 2020-03-03
	const MIN_HOUR_COST = 500;

    public static function tableName()
    {
        return 'user_type';
    }

    const TYPE_PHOTOGRAPHER = 1;
    const TYPE_VIDEOGRAPHER = 2;
    const TYPE_STUDIO = 3;
    const TYPE_MODEL = 4;
    const TYPE_STYLIST = 5;


	
    public static function getTypes()
    {
        return [
            self::TYPE_PHOTOGRAPHER => 'Фотограф',
            self::TYPE_VIDEOGRAPHER => 'Видеооператор',
            self::TYPE_STUDIO => 'Студия',
            self::TYPE_MODEL => 'Модель',
            self::TYPE_STYLIST => 'Стилист',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->parent_email) {
            /** @var \app\models\user\User $parent */
            $parent = User::find()->where(['email' => $this->parent_email])->one();
            
            if($parent){
                $this->user_id = $parent->id;
            }
        }

        if ($insert) {
            $this->created_date = new Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    private static $_userType =[];
    public $cat_name;
    public $parent_email;
    public $user_city;
    public $last_ip;
    public static $arrStatus=[
        0=>'Отключен',
        1=>'Доступен',
        2=>'Активный',
    ];

    public static $arrStatus1 =[
        //1=>'Не подтвержден',
        2=>'Опубликован',
        3=>'Заблокирован',
    ];

    public function deleteRecursive($relations = []) {

        if (is_file(__DIR__ . "/../uploads/user/avatar/{$this->avatar}")) {
            unlink(__DIR__ . "/../uploads/user/avatar/{$this->avatar}");
        }
        $media = UserMedia::find()->where(['user_type_id'=>$this->id])->all();
        foreach ($media as $m){
            if(is_file("../uploads/user/{$this->user_id}/{$m->name}")) {
                unlink("../uploads/user/{$this->user_id}/{$m->name}");
            }
        }


        FileHelper::removeDirectory($_SERVER['DOCUMENT_ROOT'] . "/images/photos/o/f/$this->city_id/$this->id", 0777);
        FileHelper::removeDirectory($_SERVER['DOCUMENT_ROOT'] . "/images/photos/o/p/$this->city_id/$this->id", 0777);

        if($relations == [])
            $relations = [ "likes", 'news', 'userCompetitions', "userGenres", "userMedia", "userNotices", "userReviews","userReviews0",];

        parent::deleteRecursive($relations);

    }

    public $un;
    public $sn;
    public $login;
    public $cityName;
    public $countryName;
    public $user_type_type_name;
    public $user_to_type_id;
    public $nam;
    public $ava;
    public $idu;
    public $u_st;
    public $params=[];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['user_id', 'city_id', 'raiting', 'type_id', 'like_style_id', 'skype', 'created_date', 'status'], 'required'],
            [['user_id', 'raiting', 'type_id', 'like_style_id', 'status', 'pro_status', 'pro_template', 'hour_cost', 'day_cost'], 'integer'],
            [['description'], 'string'],
            [['created_date', 'pro_date', 'city_id', 'last_visit', 'user_to_type_id', 'nam', 'ava', 'idu','phone','skype', 'parent_email', 'show_flash'], 'safe'],
            ['show_in_cat', 'boolean'],

            //[['avatar', 'phone', 'skype'], 'string', 'max' => 100],
            [['post_address', 'site_url', 'soc_vk', 'soc_vk_group', 'soc_facebook', 'soc_ok', 'soc_instagram', 'soc_twitter', 'soc_linkedin',
                'soc_vimeo', 'name', 'second_name'], 'string', 'max' => 255],
           // [['site_url', 'soc_vk', 'soc_vk_group', 'soc_facebook', 'soc_ok', 'soc_instagram', 'soc_twitter', 'soc_linkedin', 'soc_vimeo'], 'url'],
//            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'city_id' => 'Город',
            'avatar' => 'Аватар',
            'raiting' => 'Рейтинг',
            'type_id' => 'Тип',
            'like_style_id' => 'Like Style ID',
            'description' => 'Описание',
            'post_address' => 'Контактный адрес',
            'phone' => 'Телефон',
            'site_url' => 'Сайт Url',
            'soc_vk' => 'Vk',
            'soc_vk_group' => 'Vk Group',
            'soc_facebook' => 'Facebook',
            'soc_ok' => 'Ok',
            'soc_instagram' => 'Instagram',
            'soc_twitter' => 'Twitter',
            'soc_linkedin' => 'Linkedin',
            'soc_vimeo' => 'Vimeo',
            'skype' => 'Skype',
            'created_date' => 'Дата создания',
            'status' => 'Статус',
            'pro_status' => 'Pro Статус',
            'pro_date' => 'Дата окончания Pro',
            'last_visit' => 'Последний визит',
            'last' => 'Последний визит',
            'pro_template' => 'Pro шаблон',
            'cityName'=>'Город',
            'hour_cost'=>'Цена за час',
            'day_cost'=>'Цена за день',
            'name' => 'Имя',
            'second_name' => 'Фамилия',
            'cat_name' => 'Категория',
            'user_city' => 'Город',
            'user_type_type_name' => 'Мультиаккаунт',
            'last_ip' => 'Последний IP',
            'userlink' => 'Пользователь',
            'main_genre_id' => 'Основной жанр',
            'show_in_cat' => 'Показывать в категориях блок "О себе"',
        ];
    }

    public function getUserlink()
    {
        return '<a href="/admin/user/update?id='.$this->user_id.'">'.$this->user_id.'</a>';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBestphotographers()
    {
        return $this->hasMany(Bestphotographer::className(), ['user_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Likes::className(), ['user_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['user_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCompetitions()
    {
        return $this->hasMany(UserCompetition::className(), ['user_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGenres()
    {
        return $this->hasMany(UserGenre::className(), ['user_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserMedia()
    {
        return $this->hasMany(UserMedia::className(), ['user_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotices()
    {
        return $this->hasMany(UserNotice::className(), ['user_from_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotices0()
    {
        return $this->hasMany(UserNotice::className(), ['user_to_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserReviews()
    {
        return $this->hasMany(UserReview::className(), ['user_from_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserReviews0()
    {
        return $this->hasMany(UserReview::className(), ['user_to_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainGenre()
    {
        return $this->hasOne(Genre::className(), ['id' => 'main_genre_id']);
    }

    public function getRealType()
    {
        return Type::find()->where([
            'city_id' => $this->city_id,
            'name' => $this->type->name,
        ])->one();
    }

    public function getCategoryName()
    {
        switch ($this->type_id){
            case self::TYPE_PHOTOGRAPHER:
                return 'Фотограф';
                break;
            case self::TYPE_VIDEOGRAPHER:
                return 'Видеоопреатор';
                break;
            case self::TYPE_STUDIO:
                return 'Фотостудия';
                break;
            case self::TYPE_MODEL:
                return 'Модель';
                break;
            case self::TYPE_STYLIST:
                return 'Стилист';
                break;
            default:
                return 'Гость';
        }
    }

    public function isPro()
    {
        return $this->pro_status == 1 || !$this->realType->isActive();
    }

    /**
     * @param int $id
     * @return void
     * @throws \yii\db\Exception
     */
    public static function setUserType($id =0)
    {
        $q = static::find()->where(['user_id'=>Yii::$app->user->id]);
        if($id>0){
            $q->andWhere(['id'=>$id]);
        }else{
            $q->orderBy(['last_visit'=>SORT_ASC]);
        }


        $up = $q->one();
        if($up){
            Yii::$app->db->createCommand("UPDATE user_type SET status = 1 WHERE status!=0 AND user_id=".Yii::$app->user->id)->execute();
            Yii::$app->db->createCommand("UPDATE user_type SET status = 2, last_visit=NOW() WHERE id=".$up->id)->execute();
        }


    }

    public static function getUserType($r= false)
    {
        if(empty(self::$_userType)||$r){
            $id = Yii::$app->user->id;
//echo '<pre>';print_r($r);exit;
            $usertype = static::findOne(['user_id'=>$id, 'status'=>2]);

            if(!$usertype){
                Yii::$app->user->logout();   //asd er
            }

            self::$_userType = $usertype;

            if(!empty(self::$_userType->type->name)){
                if(self::$_userType->type->name=='Фотомодель'){
                    self::$_userType->params=Yii::$app->params['model']['simple'];
                }else if(self::$_userType->type->name!='Видеооператор'){
                    $countUserAlbum = UserGenre::find()->where(['user_type_id'=>self::$_userType->id])->count();
                    $countUservideos = UserMedia::find()->where(['user_type_id'=>self::$_userType->id])->andWhere(['type' => 2])->count();
                    if(self::$_userType->pro_status || (self::$_userType->realType && !self::$_userType->realType->isActive())){
                        self::$_userType->params=Yii::$app->params['all']['pro'];
                    }else{
                        self::$_userType->params=Yii::$app->params['all']['simple'];
                    }
                    self::$_userType->params['a']-=$countUserAlbum;
                    self::$_userType->params['v']-=$countUservideos;
                }else{

                    if(self::$_userType->pro_status || !self::$_userType->realType->isActive()){
                        self::$_userType->params=Yii::$app->params['video']['pro'];
                    }else{
                        self::$_userType->params=Yii::$app->params['video']['simple'];
                    }

                }
            }

        }

        //if(self::$_userType != false){
            //echo '<pre>';print_r(self::$_userType);exit;
        //}

        return self::$_userType;
    }

    public static function getOtherUserType()
    {
        return static::find()->where(['user_id'=>Yii::$app->user->id])->andWhere(['user_type.status'=>1])->joinWith(['city','type'])->asArray()->all();
    }

    public function getLast()
    {
        $visit = UserVisits::find()->where(['user_id' => $this->id])->orderBy('id DESC')->one();
        if($visit == false){
            return '11-11-11';
        }else{
            return $visit->date_visit;
        }

    }

    public function getLastIp()
    {
        $visit = UserVisits::find()->where(['user_id' => $this->id])->orderBy('id DESC')->one();
        return ($visit) ? $visit->ip : '';
    }

    public function createAvatar()
    {
        $file = '/var/www/artlist/web/images/photos/ava/'.$this->city_id.'/'.$this->user_id.'.jpg';
        if(file_exists($file)){
            return 'http://artlist/images/photos/ava/'.$this->city_id.'/'.$this->user_id.'.jpg';
        }else{
            return '/img/ava_null.png';
        }

        //-----------------------
        if($this->avatar != ''){
            if(stristr($this->avatar, 'https://')){
                return $this->avatar;
            }
            elseif($this->avatar == 'def_ava.jpg'){
                return '/img/ava_null.png';
            }
            else{
				//return 'https://artlist.pro/images/photos/o/p/'.$this->city_id.'/'.$this->id.'/'.$this->avatar;
                return 'http://artlist/images/photos/o/p/'.$this->city_id.'/'.$this->id.'/'.$this->avatar;
            }
        }
        else{
            return '/img/ava_null.png';
        }
    }

    public function createAvatarlazy()
    {
        $file = '/var/www/artlist/web/images/photos/ava50/'.$this->city_id.'/'.$this->user_id.'.jpg';
        if(file_exists($file)){
            return 'http://artlist/images/photos/ava50/'.$this->city_id.'/'.$this->user_id.'.jpg';
        }else{
            return '/img/ava_null.png';
        }

        //-----------------------------------
        if($this->avatar != ''){
            if(stristr($this->avatar, 'https://')){
				$lazyava = str_replace("n/p", "n/p_lazy", $this->avatar);
				$avaparts = explode("/", $this->avatar);
				if (file_exists("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$avaparts[7]."/".$avaparts[8]."/".$avaparts[9]."/".$avaparts[10]."/".$avaparts[11]))
				{
					return $lazyava;
					echo "<script>alert('');</script>";
				}
				else  
				{
					if(!is_dir("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$avaparts[7]."/".$avaparts[8]."/".$avaparts[9]."/".$avaparts[10]))
					{
					BaseFileHelper::createDirectory("/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$avaparts[7]."/".$avaparts[8]."/".$avaparts[9]."/".$avaparts[10], 0755);
					}
					$image = "/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p/".$avaparts[7]."/".$avaparts[8]."/".$avaparts[9]."/".$avaparts[10]."/".$avaparts[11];
					$imagelazy = "/var/www/www-root/data/www/artlist.pro/web/images/photos/n/p_lazy/".$avaparts[7]."/".$avaparts[8]."/".$avaparts[9]."/".$avaparts[10]."/".$avaparts[11];
					if(file_exists($image))
                    {
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $imageSize = $manager->make($image);
                        $imageSize->resize(50,null,  function($img) {
                            $img->aspectRatio();
                        });
                        $imageSize->save($imagelazy);
                    }
					return $lazyava;
				}
            }
            elseif($this->avatar == 'def_ava.jpg'){
                return '/img/ava_null.png';
            }
            else{
				return 'http://artlist/images/photos/o/p_lazy/'.$this->city_id.'/'.$this->id.'/'.$this->avatar;
            }
        }
        else{
            return '/img/ava_null.png';
        }
    }

    public function getAvatarPath()
    {
        if($this->avatar != ''){
            if(stristr($this->avatar, 'http')){
                $pieces = explode('/images/', $this->avatar);
                return $_SERVER['DOCUMENT_ROOT'].'/images/'.$pieces[1];
            }
            elseif($this->avatar == 'def_ava.jpg'){
                return '';
            }
            else{
                if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/photos/o/p/'.$this->city_id.'/'.$this->id.'/'.$this->avatar)){
                    return $_SERVER['DOCUMENT_ROOT'].'/images/photos/o/p/'.$this->city_id.'/'.$this->id.'/'.$this->avatar;
                }

                elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/city_'.$this->city_id.'/'.$this->id.'/ava_'.$this->avatar)){
                    return $_SERVER['DOCUMENT_ROOT'].'/images/city_'.$this->city_id.'/'.$this->id.'/ava_'.$this->avatar;
                }
            }
        }
        else{
            return '';
        }
    }

    /**
     * @param $count
     * @return array|UserMedia[]
	 * Теперь по ссылке возвращает выбранный случайный жанр ЧАВ 2020-02-11
     */
    public function getRandomMedia($count, &$genre_id = null)
    {
        //$genre_id = 0;
        if($this->type_id == UserType::TYPE_VIDEOGRAPHER){
            $q = UserMedia::find()->where(['user_type_id' => $this->id, 'type' => UserMedia::TYPE_VIDEO]);
            if(!$this->isPro()) $count = 3;
        }
        elseif($this->type_id == UserType::TYPE_MODEL || $this->type_id == UserType::TYPE_STYLIST || $this->type_id == UserType::TYPE_STUDIO){
            $q = UserMedia::find()->where(['user_type_id' => $this->id ]);
        }
        else {
            if (!$genre_id && $this->main_genre_id) {
                $genre_id = $this->main_genre_id;
            }
            if ($genre_id === null || $genre_id === 0) {
                $genres = UserMedia::find()
                    ->select('user_media.*, genre.name as g_name')
                    ->leftJoin('genre', 'genre.id = user_media.genre_id')
                    ->where(['user_type_id' => $this->id])
                    ->andWhere('genre_id IS NOT NULL')
                    ->andWhere(['>', '(SELECT COUNT(*) from user_media AS um where um.genre_id = user_media.genre_id and um.user_type_id = user_media.user_type_id)', 4])
                    ->groupBy('genre_id')
                    ->all();

                if (count($genres)) {
                    $media = $genres[array_rand($genres, 1)];
                    $genre_id = $media->genre_id;
                    $q = UserMedia::find()->where(['user_type_id' => $this->id, 'genre_id' => $genre_id]);
                } else {
                    $user_genres = UserMedia::find()
                        ->select('user_media.*, (SELECT COUNT(*) from user_media AS um where um.user_genre_id = user_media.user_genre_id and um.user_type_id = user_media.user_type_id and um.is_cover = 0) as count_media, user_genre.name as g_name')
                        ->leftJoin('user_genre', 'user_genre.id = user_media.user_genre_id')
                        ->where(['user_media.user_type_id' => $this->id])
                        ->andWhere('user_genre_id IS NOT NULL')
                        ->andWhere(['>', '(SELECT COUNT(*) from user_media AS um where um.user_genre_id = user_media.user_genre_id and um.user_type_id = user_media.user_type_id)', 4])
                        ->groupBy('user_genre_id')
                        ->all();
                    if (count($user_genres)) {
                        $media = $user_genres[array_rand($user_genres, 1)];
                        $user_genre_id = $media->user_genre_id;
                        $q = UserMedia::find()->where(['user_type_id' => $this->id, 'user_genre_id' => $user_genre_id]);
                    } else {
                        return [];
                    }
                }

            } else {
                $genre_id = ($genre_id == 0) ? null : $genre_id;
                $q = UserMedia::find()->where(['user_type_id' => $this->id, 'genre_id' => $genre_id]);
            }
        }

        if($this->type_id == UserType::TYPE_PHOTOGRAPHER){
          //  if($this->isPro())
                $q->orderBy('sort ASC');
         //   else
         //       $q->orderBy('id DESC');
        }
        else{
            $q->orderBy(new Expression('rand()'));
        }

        if($this->type_id == UserType::TYPE_PHOTOGRAPHER)
            $q->offset(1);

        $q->with('user');
        $q->limit($count);
//echo '<pre>';print_r($q->createCommand()->rawSql);
        return $q->all();
    }

    /**
     * @param $genre_id
     * @return int
     */
    public function allowUpload($genre_id)
    {
        $media = UserMedia::find()->where(['user_type_id' => $this->id, 'genre_id' => $genre_id, 'type' => UserMedia::TYPE_PHOTO])->count();

        if($this->pro_status)
            return  50 - $media;
        else
            return  20 - $media;
    }

    public function getVideos()
    {
        return $this->hasMany(UserMedia::className(), ['user_type_id' => 'id'])->where(['user_media.type' => UserMedia::TYPE_VIDEO]);
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function isAvailable()
    {
        if($this->avatar == '' || $this->avatar == 'def_ava.jpg')
            return false;

        switch ($this->type_id){
            case UserType::TYPE_PHOTOGRAPHER:
                $q = Yii::$app->db->createCommand("select count(*) as c
                        from 
                        (
                                select distinct genre.id as c
                                            from genre 
                                                join user_media
                                                    on user_media.genre_id = genre.id
                                                where user_media.user_type_id = '{$this->id}'
                                                        and (SELECT COUNT(*) from user_media AS um where um.genre_id = user_media.genre_id and um.user_type_id = user_media.user_type_id) > 4
                                        union all
                                        select distinct user_genre.id as c
                                            from user_genre 
                                                join user_media
                                                      on user_media.user_genre_id = user_genre.id
                                                where user_media.user_type_id = '{$this->id}'
                                                        and (SELECT COUNT(*) from user_media AS um where um.user_genre_id = user_media.user_genre_id and um.user_type_id = user_media.user_type_id) > 4
                        ) AS t1 ")
                    ->queryAll();
                if($q[0]['c'] == 0)
                    return false;
                break;
            case UserType::TYPE_STUDIO:
                if(UserMedia::find()->where(['user_type_id' => $this->id])->count() == 0)
                    return false;
                break;
            case UserType::TYPE_MODEL:
                if(UserMedia::find()->where(['user_type_id' => $this->id])->count() == 0)
                    return false;
                break;
            case UserType::TYPE_STYLIST:
                if(UserMedia::find()->where(['user_type_id' => $this->id])->count() == 0)
                    return false;
                break;
            case UserType::TYPE_VIDEOGRAPHER:
                    if(UserMedia::find()->where(['type' => UserMedia::TYPE_VIDEO, 'user_type_id' => $this->id])->count() == 0)
                        return false;
                break;
        }

        return true;
    }

   // public static function find()
   // {
   //     return new UserTypeQuery(get_called_class());
   // }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function reasons()
    {
        $reasons = [];

        if($this->avatar == '' || $this->avatar == 'def_ava.jpg')
            $reasons[] = 'Не добавлена аватарка';

        switch ($this->type_id){
            case UserType::TYPE_PHOTOGRAPHER:
                $q = Yii::$app->db->createCommand("select count(*) as c
                        from 
                        (
                                select distinct genre.id as c
                                            from genre 
                                                join user_media
                                                    on user_media.genre_id = genre.id
                                                where user_media.user_type_id = '{$this->id}'
                                                        and (SELECT COUNT(*) from user_media AS um where um.genre_id = user_media.genre_id and um.user_type_id = user_media.user_type_id) > 4
                                        union all
                                        select distinct user_genre.id as c
                                            from user_genre 
                                                join user_media
                                                    on user_media.user_genre_id = user_genre.id
                                                where user_media.user_type_id = '{$this->id}'
                                                        and (SELECT COUNT(*) from user_media AS um where um.user_genre_id = user_media.user_genre_id and um.user_type_id = user_media.user_type_id) > 4
                        ) AS t1 ")
                    ->queryAll();


                if($q[0]['c'] == 0)
                    $reasons[] = 'Не заполнено портфолио';
                break;
            case UserType::TYPE_STUDIO:
                if(UserMedia::find()->where(['user_type_id' => $this->id])->count() == 0)
                    $reasons[] = 'Не заполнено портфолио';
                break;
            case UserType::TYPE_MODEL:
                if(UserMedia::find()->where(['user_type_id' => $this->id])->count() == 0)
                    $reasons[] = 'Не заполнено портфолио';
                break;
            case UserType::TYPE_STYLIST:
                if(UserMedia::find()->where(['user_type_id' => $this->id])->count() == 0)
                    $reasons[] = 'Не заполнено портфолио';
                break;
            case UserType::TYPE_VIDEOGRAPHER:
                if(UserMedia::find()->where(['type' => UserMedia::TYPE_VIDEO, 'user_type_id' => $this->id])->count() == 0)
                    $reasons[] = 'Не заполнено портфолио';
                break;
        }
        return $reasons;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isBest()
    {

        $date = new DateTime();
		$current_m = $date->format('m');
		$prev_m = strtotime(date('Y-'.$current_m.'-01 00:00:00')) - 1;

        $unixDateEnd = $prev_m;

        $best =  Bestphotographer::find()
            ->where(['city_id' => $this->city_id])
            ->andWhere( ['>', 'created_at', $unixDateEnd])
            ->orderby(['id' => SORT_DESC])
            ->one();

        return $best && ($best->userType->id == $this->id);
    }

    public function createLink()
    {
        if (!empty(\Yii::$app->authManager->getRolesByUser($this->user->id)['media'])){
            return Url::to(["/site/personal-page", 'id' => $this->id]);
        }
        if (!empty(\Yii::$app->authManager->getRolesByUser($this->user->id)['user'])){
            $mystring = $this->user->email;

            $pieces = explode("@", $this->user->email);

            $findme = '@odnoklassniki.com';
            $pos = strpos($mystring, $findme);
            if ($pos !== false) {
                return 'https://ok.ru/profile/' . $pieces[0];
            }
            $findme = '@ok.com';
            $pos = strpos($mystring, $findme);
            if ($pos !== false) {
                return 'https://ok.ru/profile/' . $pieces[0];
            }
            $findme = '@vkontakte.com';
            $pos = strpos($mystring, $findme);
            if ($pos !== false) {
                return 'https://vk.com/id' . $pieces[0];
            }
            $findme = '@vk.com';
            $pos = strpos($mystring, $findme);
            if ($pos !== false) {
                return 'https://vk.com/id' . $pieces[0];
            }
            $findme = '@facebook.com';
            $pos = strpos($mystring, $findme);
            if ($pos !== false) {
                return 'https://facebook.com/profile.php?id=' . $pieces[0];
            }
        }
        else{
            return Url::to(["/site/personal-page", 'id' => $this->id]);
        }
    }

    public function isGuest()
    {
        return ($this->user->login == '' && $this->user->pass == '' && $this->type_id == 0);
    }

    public function isUser()
    {
        return ($this->type_id != 0 && !$this->isAdmin());
    }

    public function isAdmin()
    {
        return AdminRules::find()->where(['user_id' => $this->user->id])->exists();
    }

    public function showContacts()
    {
        return $this->isPro() || (time() - $this->show_contacts) < 86400;
    }

    public function getWins()
    {
        return UserCompetition::find()->where(['place' => 1, 'user_type_id' => $this->id])->all();
    }

    /**
     * рейтинг, полученный за исходящие комменты/лайки за последний день
     * @return float|int
     */
    public function getOutputRatingByDay()
    {
        $startdate = date('Y-m-d 00:00:00');

        $likes = Likes::find()->where(['user_type_id' => $this->id])->andWhere(['>', 'created_at', strtotime($startdate)])->count();
        $comments = UserReview::find()->where(['user_from_type_id' => $this->id])->andWhere(['>', 'created_date', $startdate])->count();

        return ($likes * 2) + ($comments * 2);
    }

    /**
     * рейтинг, полученный за входящие комменты/лайки за последний день
     * @return float|int
     */
    public function getInputRatingByDay()
    {
        $startdate = date('Y-m-d 00:00:00');

        $likes = Likes::find()
            ->leftJoin('user_media', 'user_media.id = likes.user_media_id')
            ->leftJoin('user_type', 'user_type.id = user_media.user_type_id')
            ->where(['user_type.id' => $this->id])
            ->andWhere(['>', 'created_at', strtotime($startdate)])
            ->count();

        $comments = UserReview::find()
            ->leftJoin('user_media', 'user_media.id = user_review.user_media_id')
            ->leftJoin('user_type', 'user_type.id = user_media.user_type_id')
            ->where(['user_type.id' => $this->id])
            ->andWhere(['>', 'user_review.created_date', $startdate])
            ->count();

        return $likes + $comments;
    }

    /**
     * пользователь уже комментил это медиа сегодня
     * @return bool
     */
    public function alreadyReviewed($media)
    {
        $startdate = date('Y-m-d 00:00:00');

        $query = UserReview::find()->where(['user_from_type_id' => $this->id])->andWhere(['>', 'created_date', $startdate]);

        if($media->breed == 'media'){
            $query->andWhere(['user_media_id' => $media->id]);
        }
        elseif($media->breed == 'competition'){
            $query->andWhere(['user_competition_id' => $media->id]);
        }

        return $query->exists();
    }
	
	// ЧАВ 2020-01-28 
	// Есть функция isPro и ЭТА попрошу не путать! ))
	public function isProForRating() {
		return $this->pro_status == 1 && $this->realType->isActive();
	}
	
	public function updateProRating() {
		 if($this->isProForRating() && Yii::$app->params['todayIsFirstVisit'] && !self::$_ratingUpdated) {
			   $this->raiting += 10;
			   $this->save();
			   self::$_ratingUpdated = true;
		 }
	}
	
	public function updateRating() 
	{
		 if(!$this->isProForRating() && !self::$_ratingUpdated) {
			$cnt = UserMediaViewHistory::find()
					->where(['media_viewer_id'=>$this->id,'date'=>date("Y-m-d")])
					->count();
			if ($cnt == 5) {
				$this->raiting += 3;
				$this->save();
				self::$_ratingUpdated = true;
			}
		 }
	}
	
	public static function getRatingFlashInfo() 
	{
	   $info = new \stdClass();
	   $info->msg = '';
	   $info->hide_url = '';
	   $info->show = false;
	   $info->first_show = false;
	   //$info->_is_first_visit = Yii::$app->params['todayIsFirstVisit'];
	   $info->_was_updated = self::$_ratingUpdated;
	   $user = self::getUserType();
	   if (!$user) return $info;
	   
	   if (self::$_ratingUpdated) {
		   $info->msg = 'Вам начислены баллы к рейтингу за посещение';
		   $info->show = $user->show_flash;
		   $info->hide_url = \app\components\Url::to(['/site/hide-flash']);
		   $info->first_show = true;
	   }
	   elseif (!$user->isProForRating()) {
			$cnt = UserMediaViewHistory::find()
					->where(['media_viewer_id'=>$user->id,'date'=>date("Y-m-d")])
					->count();
			if ($cnt<5) {
				$n = 5-$cnt;
				if ($n==1)
					$photo_text = 'фотографию';
				elseif ($n<=4)
					$photo_text = 'фотографии';
				else
					$photo_text = 'фотографий';
				
				$info->msg = "Посмотрите сегодня еще $n фото или видео и получите бонус к рейтингу";
				$info->show = $user->show_flash_counter && $user->hide_flash_counter_for_date != date('Y-m-d') ;
				$info->hide_url = \app\components\Url::to(['/site/hide-flash-counter']);
				$info->first_show = ($cnt == 0 && Yii::$app->params['todayIsFirstVisit']);
			}
	   }
	   return $info;
	}
	
	
	public function getMinCostInfo($cnt = 4) {
		if ($this->type_id != self::TYPE_PHOTOGRAPHER) {
			return $this->getCostInfo();
		}
		
		$result = [
				'show_hour_cost'=>true,
				'show_day_cost'=>false,
				'hour_cost'=>self::MIN_HOUR_COST,
				'day_cost'=>null,
		];
		
		// join - проверяем что в альбоме есть материалы.
		$userCost = (new Query)
			->select('min(uc.hour_cost) as min_cost')
  			->from('user_genre_cost uc')
			->where(['uc.user_type_id'=>$this->id])
			->andWhere(['not','uc.hour_cost is null'])
			->andWhere(['>','(select count(*) from user_media um where um.user_type_id = "'.$this->id .'"  AND um.genre_id = uc.genre_id)',$cnt])
		//	->createCommand()->getRawSql();
			->one();
	//	var_dump($userCost);
	//	die();
		if ($userCost) {
			$result['hour_cost'] = $userCost['min_cost'];
		}
		
		return $result;
	}
	
	public function getCostInfo($genre_id = null) {
		$result = [
			'show_hour_cost'=>true,
			'show_day_cost'=>false,
//			'hour_cost_name'=>'Цена за услугу',
//			'day_cost_name'=>'Цена за день',
			'hour_cost'=>null,
			'day_cost'=>null,
		];
		
		$hour_cost = ($this->hour_cost === null) ? self::MIN_HOUR_COST : $this->hour_cost;
		
		if ($this->type_id == self::TYPE_PHOTOGRAPHER) {
			if ($genre_id && $genre_id<=18) {
				$userCost = UserGenreCost::find()
					->where(['user_type_id'=>$this->id,'genre_id'=>$genre_id])
					->one();
				// если нет фоток цену не возвращает ЧАВ
				$result['hour_cost'] = ($userCost && $userCost->hour_cost) ? $userCost->hour_cost : self::MIN_HOUR_COST; 
					
				if ($genre_id == 3) { // Свадебный - 2 цены
					$result['show_day_cost'] = true;
					$result['day_cost'] = ($userCost) ? $userCost->day_cost : null;
				}
			}
		} elseif ($this->type_id == self::TYPE_VIDEOGRAPHER) {
			
			$result['hour_cost'] = $hour_cost;
			$result['show_day_cost'] = true;
			$result['day_cost'] = $this->day_cost;
			
		} 	elseif ($this->type_id == self::TYPE_STUDIO) {
			
			$result['hour_cost'] = $hour_cost;
//			$result['hour_cost_name'] = 'Стоимость аренды';			
			
		} elseif ($this->type_id == self::TYPE_MODEL) { // Для модели цен нет
		
			$result['show_hour_cost'] = false; 
			$result['show_day_cost'] = false; 
				
		} elseif ($this->type_id == self::TYPE_STYLIST) {
			
			$result['hour_cost'] = $hour_cost;
		}
	
		return $result;
	}

	public function deactivateProStatus()
    {
        $this->pro_status = 0;
        if ($this->show_in_cat == 1 && $this->realType->isActive()) {
            $this->show_in_cat = 0;
        }
        $this->save(false);
    }

	public function deactivateProStatusWhereNoActive()
    {
		if (!$this->realType->isActive()) {
			$this->pro_status = 0;
			$this->pro_date = "";			
		}
        $this->save(false);
    }
	
	/*
	public function todayIsFirstVisit() {
		if (! isset(Yii::$app->params['todayIsFirstVisit']) ) {
		}
		return (Yii::$app->params['todayIsFirstVisit']);
	}
	*/
	
}
