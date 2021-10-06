<?php

namespace app\modules\artlist\models;

use Yii;

/**
 * This is the model class for table "blocks".
 *
 * @property int $id
 * @property int $city_id
 * @property int $popularblock
 * @property int $genreblock
 * @property int $weddingphotos
 * @property int $artistmodule
 * @property int $blogmodule
 * @property int $contestmodule
 * @property int $mapmodule
 * @property int $textmodule
 * @property int $adv_1
 * @property int $adv_2
 * @property string $map
 *
 * @property City $city
 */
class Blocks extends DbModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blocks';
    }
    public $cityName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'popularblock', 'genreblock', 'weddingphotos', 'artistmodule', 'blogmodule', 'contestmodule', 'mapmodule', 'textmodule', 'adv_1', 'adv_2'], 'integer'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['map'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'popularblock' => 'Популярные фотографы',
            'genreblock' => 'Жанры съемки',
            'weddingphotos' => 'Случайный жанр',
            'artistmodule' => 'Фотограф месяца',
            'blogmodule' => 'Новое в блогах',
            'contestmodule' => 'Конкурс фотографии',
            'mapmodule' => 'Карта',
            'textmodule' => 'Сео текст',
            'cityName' => 'Город',
            'adv_1' => 'Рекламный блок сверху',
            'adv_2' => 'Рекламный блок снизу',
            'map' => 'iframe для карты',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
