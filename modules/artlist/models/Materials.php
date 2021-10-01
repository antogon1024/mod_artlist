<?php
namespace app\modules\artlist\models;

use app\models\DbModel;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "materials".
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property string $description
 * @property string $text
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property int $sort
 * @property int $status
 */
class Materials extends ActiveRecord
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
        return '{{materials}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'link'], 'required'],
            [['text'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 500],
            [['meta_title'], 'string', 'max' => 80],
            [['meta_keyword'], 'string', 'max' => 100],
            [['meta_description'], 'string', 'max' => 290],
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
            'link' => 'Линка',
            'description' => 'Краткое описание',
            'text' => 'HTML',
            'meta_title' => 'Meta Title',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'sort' => 'Порядок сортировки',
            'status' => 'Статус',
        ];
    }

    public static function getLinks()
    {
        return static::find()->where(['status' => 1])->all();
    }

    public static function getLinksMenu()
    {
        $items = [];

        $items[] = ['label' => 'Блог', 'url' => ['/news/all']];

        foreach(static::find()->where(['status' => 1])->all() as $material){
            $items[] =  ['label' => $material->name, 'url' => ["site/information", 'link' => $material->link]];
        }

        $items[] = ['label' => 'Все фотографии', 'url' => ['site/all-photos', 'id' => 0]];

        return $items;
    }
}
