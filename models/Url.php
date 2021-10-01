<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url".
 *
 * @property int $id
 * @property int $type_id
 * @property string $referer
 * @property string $redirect
 * @property int $table_id
 */
class Url extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->dbart;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url';
    }



    public $cat;
    public $loc;
    public $name;
    public $country;
    public $city;
    public $type;
    public $title;
    public $location;
    public $genre_name;
    public $type_name;
    public $competition_name;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['table_id','redirect'], 'required'],
            [['type_id', 'table_id'], 'integer'],
            [['referer', 'redirect'], 'string'],
            [['referer'], 'unique'],
            [['redirect'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'referer' => 'Старая ссылка',
            'redirect' => 'Новая ссылка',
            'cat' => 'Категория',
            'location' => 'Гео',
            'name' => 'ФИО',
            'genre_name' => 'Жанр',
            'type_name' => 'Тип аккаунта',
            'competition_name' => 'Название конкурса',
        ];
    }
}
