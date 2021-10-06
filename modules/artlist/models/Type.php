<?php

namespace app\modules\artlist\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "type".
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property double $price
 * @property int $price_six_month
 * @property int $price_year
 * @property int $bonus
 * @property int $bonus_six_month
 * @property int $bonus_year
 * @property int $status
 *
 * @property City $city
 */
class Type extends DbModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type';
    }

    public $cityName;
    public $countryName;
    private static $arrType=[];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['city_id', 'name', 'price', 'status'], 'required'],
            [['city_id', 'status', 'price_six_month', 'price_year', 'bonus', 'bonus_six_month','bonus_year'], 'integer'],
            [['price'], 'number'],
            [['name', 'cityName', 'countryName'], 'safe'],
            //[['name'], 'string', 'max' => 100],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'Город',
            'name' => 'Спецификация',
            'price' => 'Цена за месяц',
            'price_six_month' => 'Цена за пол года',
            'price_year' => 'Цена за год',
            'bonus' => 'Бонус за месяц',
            'bonus_six_month' => 'Бонус за пол года',
            'bonus_year' => 'Бонус за год',
            'status' => 'Статус',
            'cityName' => 'Город',
            'countryName' => 'Страна',
            'city' => 'Город',
            'country' => 'Страна',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public static function getAllTypes()
    {
        if(empty(self::$arrType)){
            self::$arrType= ArrayHelper::map(static::find()->select('id, name')->asArray()->all(), 'id', 'name');
        }
        return self::$arrType;
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }
}
