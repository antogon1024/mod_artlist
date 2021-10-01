<?php

namespace app\modules\artlist\models;

use app\models\City;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string $name
 *
 * @property City[] $cities
 */
class Country extends DbModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    public $countCities;
    private static $arrComp;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
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
            'countCities' => 'Города'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['country_id' => 'id']);
    }

    public static function getAllCountries()
    {
        if(empty(self::$arrComp)){

            self::$arrComp= ArrayHelper::map(static::find()->select('id, name')->asArray()->all(), 'id', 'name');
            self::$arrComp[0]='';
            ksort(self::$arrComp);
        }
        return self::$arrComp;
    }

    public static function getUserCountryName($city_id)
    {
        $city_id = City::find()->where(['id' => $city_id])->one();
        $city_id = Country::find()->where(['id' => $city_id['country_id']])->one();
        return $city_id['name'];
    }
}
