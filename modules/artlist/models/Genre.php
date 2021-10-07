<?php

namespace app\modules\artlist\models;

//use app\models\City;
use app\models\user\UserMedia;
use app\models\user\UserType;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "genre".
 *
 * @property int $id
 * @property string $name
 * @property string $img
 * @property int $sort
 * @property int $status
 * @property int $type
 *
 * @property City $city
 */
class Genre extends DbModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'genre';
    }

    public $userUnique;
    public $image;
    public static $arrComp;
	
	// ЧАВ 2020-02-12:
	// Макисмальный id из Genre 
	// В userGenre все id - больше 
	// И такая логика в SiteController::actionPersonalAlbom
	const MAX_PHOTO_PUBLIC_GENRE_ID = 18; 
	public const URL_GENRE_IDS = [
		'portret' =>	1,
		'svadba' =>	3,
		'studio' =>		2,
		'children' =>	4,
		'photobook' =>	5,
		'nude' =>		6,
		'lovestory' =>	7,
		'nature' =>		8,
		'portfel'=>		9,
		'subject' =>	10,
		'family' =>		11,
		'pregnancy' =>	12,
		'corporate' =>	13,
		'retouch' =>	14,
		'collage' =>	15,
		'animals' =>	16,
		'interior' =>	17,
		'reportage' =>	18,
	];
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'img', 'sort'], 'required'],
            [[/*'city_id'*/ 'sort', 'status', 'type'], 'integer'],
            //[['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'gif, jpeg, jpe, jpg, png'],
            //
            [['name', 'img'], 'string', 'max' => 255],
            //[[/*'city_id'],*/ 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            //'city_id' => 'Город',
            'name' => 'Имя',
            'img' => 'Аватар жанра',
            'sort' => 'Порядок сортировки',
            'status' => 'Статус',
            'type' => 'Тип',
        ];
    }

    /*
    
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
     */

    public static function getAllGenres()
    {
        if(empty(self::$arrComp)){
            self::$arrComp= ArrayHelper::map(static::find()->select('id, name')->asArray()->all(), 'id', 'name');
        }
        return self::$arrComp;
    }

    /**
     */
    public function getUsersCount()
    {
       $city = Yii::$app->params['city_iden'];

        try {
            $r = Yii::$app->db->createCommand("
                select *
                    from user_media
                    left join user_type 
                        on user_type.id = user_media.user_type_id 
                    left join user 
                         on user_type.user_id = user.id 
                    where user_media.genre_id = '{$this->id}'
                    and user_type.avatar <> ''
                    and user_type.city_id = '{$city}'
                    and (select count(*) from admin_rules where admin_rules.user_id = user.id) < 1
                    group by user_media.user_type_id
                    HAVING COUNT(user_media.user_type_id) >= 5")->execute();
        } catch (Exception $e) {
            return 0;
        }

        return $r;
    }

    public function getMedias()
    {
        return $this->hasMany(UserMedia::className(), ['genre_id' => 'id']);
    }
}
