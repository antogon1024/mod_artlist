<?php

namespace app\modules\artlist\models;

use app\models\user\UserType;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $name
 * @property string $case
 * @property string $url
 * @property int $country_id
 * @property int $sortOrder
 * @property int $bold
 * @property int $show
 * @property int $show_only_photos
 *
 * @property Advertisement[] $advertisements
 * @property Blocks[] $blocks
 * @property Country $country
 * @property Competition[] $competitions
 * @property Genre[] $genres
 * @property Type[] $types
 * @property UserType[] $userTypes
 * @property Bestphotographer $best
 */
class City extends DbModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{city}}';
    }
    public $countryName;
    public $city_name_english;
    private static $arrComp;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'case'], 'required'],
            [['country_id'], 'integer'],
            [['countryName', 'bold', 'show', 'show_only_photos', 'allreviews', 'mark'], 'safe'],
            [['name', 'url'], 'string', 'max' => 100],
            [['url'], 'unique'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'url' => 'Url',
            'country_id' => 'Country ID',
            'countryName' => 'Страна',
            'case' => 'Родительный падеж',
            'best' => 'Фотограф месяца',
            'bestId' => 'ID Фотограф месяца',
            'bestLink' => 'Фотограф месяца',
            'bold' => 'Выделение',
            'show' => 'Показывать',
            'htmlStatus' => 'Статус',
            'show_only_photos' => 'Показывать случайные фотографии только этого города',
            'allreviews' => 'Общее количество отзывов',
            'mark' => 'Средняя оценка отзывов',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertisements()
    {
        return $this->hasMany(Advertisement::className(), ['city_id' => 'id']);
    }

    public function getBest()
    {
        return $this->hasOne(Bestphotographer::className(), ['city_id' => 'id'])->orderBy('created_at DESC');
    }

    public function getBestId()
    {
        return Bestphotographer::find()->where(['city_id' => $this->id])->orderBy('created_at DESC')->one()->user_type_id;
    }

    public function getBestLink()
    {
        $user =  Bestphotographer::find()->where(['city_id' => $this->id])->orderBy('created_at DESC')->one();

        return '<a href="/id'.$user->userType->id.'" target="_blank">'.$user->userType->name.' '.$user->userType->second_name.'</a>';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlocks()
    {
        return $this->hasMany(Blocks::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(Competition::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenres()
    {
        return $this->hasMany(Genre::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypes()
    {
        return $this->hasMany(Type::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTypes()
    {
        return $this->hasMany(UserType::className(), ['city_id' => 'id']);
    }

    public static function getAllCities()
    {
        if(empty(self::$arrComp)){
            self::$arrComp= ArrayHelper::map(static::find()->select('id, name')->asArray()->all(), 'id', 'name');
        }
        return self::$arrComp;
    }

    public static function getAllCitiesPopup($id_country)
    {
        $cities = City::find()->where(['country_id'=>$id_country, 'show' => 1])->orderBy('sortorder')->asArray()->limit(14)->all();
        return $cities;
    }

    public static function getUserCity($city_name)
    {
        $city_id = City::find()->where(['name' => $city_name])->one();

        if($city_id){
            return $city_id->id;
        }else{
            return Yii::$app->params['defCityId'];
        }

    }

    public static function getUserCityId($city_id)
    {
        $city_id = City::find()->where(['id' => $city_id])->one();

        if($city_id){
            return $city_id->id;
        }else{
            return Yii::$app->params['defCityId'];
        }
    }

    public static function getUserCityName($city_id){
        $city_id = City::find()->where(['id' => $city_id])->one();
        return $city_id['name'];
    }


    public function getHtmlStatus()
    {
        if($this->show){
            return '<span class="badge badge-success">Виден</span>';
        }
        else{
            return '<span class="badge badge-danger">Скрыт</span>';
        }
    }
}
