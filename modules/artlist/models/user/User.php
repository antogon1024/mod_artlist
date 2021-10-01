<?php

namespace app\modules\artlist\models\user;


use app\models\City;
use app\modules\artlist\models\DbModel;
use app\models\user\UserVisits;
use Yii;
use yii\base\ErrorException;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $pass
 * @property string $name
 * @property string $sex
 * @property string $auth_key
 * @property string $second_name
 * @property string $age
 * @property string $email
 * @property integer $status
 * @property integer $confirmed
 * @property integer $unsubscribed
 * @property string $new_email
 *
 * @property UserMedia[] $userMedia
 * @property UserType[] $userTypes
 * @property UserType $firstUserType
 */
class User extends DbModel implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function deleteRecursive($relations = []) {
        if($relations == [])
            $relations = [  "userTypes",];
        parent::deleteRecursive($relations);
    }

    public $last_ip;
    public $last_visit;
    public $last;
    public $user_type_type_name;
    public $user_type;
    public $user_type_id;
    public $user_category;
    public $cat_name;
    public $user_city;
    public $id_city;
    public $status2;

    public static $arrStatus1 =[
        //1=>'Не подтвержден',
        2=>'Опубликован',
        3=>'Заблокирован',
    ];

    public static $arrStatus2 =[
        1=>'Не подтвержден',
        2=>'Подтвержден',
        3=>'',
    ];

    const STATUS_IMPORT = 0;
    const STATUS_CONFIRMED = 1;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['login', 'pass', 'name', 'sex', 'second_name', 'email', 'new_email'], 'required'],
            [['age','status2', 'confirmed', 'unsubscribed'], 'safe'],
            [['login', 'name', 'second_name'], 'string', 'max' => 100],
            [['pass'], 'string', 'max' => 150],
            [['sex'], 'string', 'max' => 10],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_type_id' => 'ID',
            'login' => 'Логин',
            'pass' => 'Пароль',
            'name' => 'Имя',
            'sex' => 'Пол',
            'second_name' => 'Фамилия',
            'age' => 'Возраст',
            'email' => 'Email',
            'new_email' => 'Новый емайл',
            'last_ip' => 'Последний айпи',
            'last_visit' => 'Дата визита',
            'user_type_type_name'=>'Мультаккаунт',        
            'status'=>'Публикация',
            'status2'=>'Подтверждение',
            'user_type'=>'Тип пользователя',
            'cat_name'=>'Категория пользователя',
            'user_city' => 'Город',
            'admin' => 'Администратор',
            'confirmed' => 'Подтверждение email',
            'unsubscribeInfo' => 'Отписан от рассылки',
            'unsubscribed' => 'Отписан от рассылки',
            'confirmedHtml' => 'Подтверждение email'
        ];
    }

    public function getConfirmedHtml()
    {
        if($this->confirmed == User::STATUS_IMPORT || !($this->confirmed)) {
            return '<span class="badge badge-danger">импортирован</span>';
        }else{
            return '<span class="badge badge-success">подтвержден</span>';
        }
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
    public function getUserTypes()
    {
        return $this->hasMany(UserType::className(), ['user_id' => 'id']);
    }

    public function getFirstUserType()
    {
        return $this->userTypes[0];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlacklist()
    {
        return $this->hasOne(Blacklist::className(), ['user_id' => 'id']);
    }


    public function getAdmin()
    {
        return AdminRules::find()->where(['user_id' => $this->id])->exists();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $user = static::findOne($id);
        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }


    /**
     * @param $username
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUsername($username)
    {
        if($user = static::find()->where(['email' => $username])->andWhere(['status'=>[1,2]])->one())
        {
            return $user;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->pass);
    }

    public function setPasswordCode($password)
    {
        $this->pass = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateUniqueRandomString($length = 32) {

        $attribute = 'auth_key';
        $randomString = Yii::$app->getSecurity()->generateRandomString($length);

        if(!$this->findOne([$attribute => $randomString]))
            return $randomString;
        else
            return $this->generateUniqueRandomString($attribute, $length);

    }

    /**
     * @param $service
     * @return User|array|null|\yii\db\ActiveRecord
     * @throws \Exception
     */
    public static function findByEAuth($service) {
        /** @var $service \nodge\eauth\ServiceBase */
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

        $sn =$service->getServiceName();
        switch ($sn){
            case 'facebook':
                $info = $service->makeSignedRequest('me', [
                    'query' => [
                        'fields' => join(',', [
                            'id',
                            'first_name',
                            'last_name',
                            'email',
                            'picture'
                        ])
                    ]
                ]);
                $attributes=[
                    'title'=>$info['first_name'].' '.$info['last_name'],
                    'first_name'=>$info['first_name'],
                    'second_name'=>$info['last_name'],
                    'email'=>$info['id'].'@facebook.com',
                    'photo'=>'https://graph.facebook.com/'.$info['id'].'/picture?type=large',
                ];

                break;
            case 'vkontakte':
                $info = $service->makeSignedRequest('users.get.json', [
                    'query' => [
                        'uids' => $service->getAttribute('id'),
                        'fields' => 'nickname, first_name, last_name,  photo, photo_100',
                        'v' => $service::API_VERSION,
                    ],
                ]);
                $info = $info['response'][0];
                $attributes=[
                    'title'=>$info['first_name'].' '.$info['last_name'],
                    'first_name'=>$info['first_name'],
                    'second_name'=>$info['last_name'],
                    'photo'=>$info['photo_100'],
                    'email'=>empty($service->getAttribute('email'))?$service->getAttribute('id').'@vk.com':$service->getAttribute('email'),
                ];
                break;
            case 'odnoklassniki':

                $attributes=[
                    'title'=>$service->getAttribute('name'),
                    'first_name'=>$service->getAttribute('first_name'),
                    'second_name'=> $service->getAttribute('last_name'),
                    'photo'=>$service->getAttribute('photo'),
                    'email'=>empty($service->getAttribute('email'))?$service->getAttribute('id').'@odnoklassniki.com':$service->getAttribute('email'),
                ];
                break;

        }

        $user = static::find()->where(['email' => $attributes['email']])->one();

        if(!$user){
            $user = new self();
            $user->name = $attributes['first_name'];
            $user->second_name = $attributes['second_name'];
            $user->email = $attributes['email'];
            $user->status = 2;
            $user->save();
            $geo_city = new UserIp();
            $cityId= City::getUserCity($geo_city->getUserIp());
            $ut = new UserType();
            $ut->user_id = $user->id;
            $ut->city_id = $cityId;
            $ut->avatar = $attributes['photo'];
            $ut->status = 2;
            $ut->name = $user->name;
            $ut->second_name = $user->second_name;
            $ut->save();
            $auth = Yii::$app->authManager;
            $r = $auth->getRole('user');
            $auth->assign($r, $user->id);
        }
        $userVisit = new UserVisits();
        $userVisit->user_id=$user->id;
        $userVisit->ip=Yii::$app->request->getUserIP();
        $userVisit->save();
        return $user;
    }

    public function getLast()
    {
        $ids = [];

        foreach ($this->userTypes as $userType) {
            $ids[] = $userType->id;
        }

       $visit = UserVisits::find()->where(['in', 'user_id' , $ids])->orderBy('id DESC')->limit(1)->all();
       return ($visit) ? $visit[0]->date_visit : '';
    }

    public function getLastIp()
    {
        $ids = [];

        foreach ($this->userTypes as $userType) {
            $ids[] = $userType->id;
        }

        $visit = UserVisits::find()->where(['in', 'user_id' , $ids])->orderBy('id DESC')->one();
        return ($visit) ? $visit->ip : '';
    }

    public function getUnsubscribeInfo()
    {
        if($this->unsubscribed)
            return '<span class="badge badge-danger">Отписан</span>';
        else
            return '';
    }
}
