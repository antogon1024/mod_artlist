<?php

namespace app\modules\artlist\models;

use app\models\interfaces\Likeable;
use app\models\user\UserCompetition;
use app\models\user\UserType;
use app\models\user\UserMedia;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Html;

/**
 * This is the model class for table "likes".
 *
 * @property int $id
 * @property int $user_competition_id
 * @property int $user_media_id
 * @property int $user_type_id
 * @property string $ip
 *
 * @property UserCompetition $userCompetition
 * @property UserType $userType
 * @property UserMedia $userMedia
 * @property Likeable $media
 */
class Likes extends DbModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['user_competition_id', 'user_media_id', 'user_type_id'], 'required'],
            [['user_competition_id', 'user_media_id', 'user_type_id'], 'integer'],
            [['user_competition_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserCompetition::className(), 'targetAttribute' => ['user_competition_id' => 'id']],
            [['user_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserType::className(), 'targetAttribute' => ['user_type_id' => 'id']],
            [['user_media_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserMedia::className(), 'targetAttribute' => ['user_media_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_competition_id' => 'User Competition ID',
            'user_media_id' => 'User Media ID',
            'user_type_id' => 'User Type ID',
            'userCompetitionName' => 'Название фотографии',
            'autorName' => 'Автор голоса',
            'autorCategory' => 'Категория',
            'created_at' => 'Дата и время',
            'type' => 'Медиа',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCompetition()
    {
        return $this->hasOne(UserCompetition::className(), ['id' => 'user_competition_id']);
    }

    public function getUserCompetitionName()
    {
        return Html::a($this->userCompetition->name, \app\components\Url::to(["/competition/photo",  'id' => $this->user_competition_id]), ['target' => '_blank']);
    }

    public function getAutorName()
    {
        return Html::a($this->userType->name.' '.$this->userType->second_name, $this->userType->createLink(), ['target' => '_blank']);
    }

    public function getAutorCategory()
    {
        return $this->userType->categoryName;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserType()
    {
        return $this->hasOne(UserType::className(), ['id' => 'user_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserMedia()
    {
        return $this->hasOne(UserMedia::className(), ['id' => 'user_media_id']);
    }

    /**
     * @return UserCompetition|UserMedia
     */
    public function getMedia()
    {
        if($this->user_media_id)
            return $this->userMedia;
        elseif($this->user_competition_id)
            return $this->userCompetition;
    }

    public function getType()
    {
        if($this->userCompetition){
            return Html::a($this->userCompetition->name, \app\components\Url::to(["/competition/photo",  'id' => $this->user_competition_id]), ['target' => '_blank']);
        }
        elseif($this->userMedia && $this->userMedia->type == UserMedia::TYPE_PHOTO){
            return Html::a("Фотография #{$this->userMedia->id}", \app\components\Url::to([
                "/site/shortlink",
                'media_id' => $this->userMedia->id,
                'type' => 'photo',
                'user_type_id' => $this->userMedia->user_type_id
            ]),
                ['target' => '_blank']);
        }
        elseif($this->userMedia && $this->userMedia->type == UserMedia::TYPE_VIDEO){
            return 'Фото';
        }
    }

    public function getMediaOwner()
    {
        return Html::a($this->media->user->name.' '.$this->media->user->second_name, \app\components\Url::to(["/site/personal-page",  'id' => $this->media->user->id]), ['target' => '_blank']);
    }
}
