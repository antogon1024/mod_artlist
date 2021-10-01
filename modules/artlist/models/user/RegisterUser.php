<?php

namespace app\modules\artlist\models\user;

use app\models\user\UserMedia;
use app\models\user\UserType;
use app\models\DbModel;
//use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\base\ErrorException;
use yii\web\IdentityInterface;

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
 *
 * @property UserMedia[] $userMedia
 * @property UserType[] $userTypes
 */
class RegisterUser extends User
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    //public  $reCaptcha;

    public function checkImport()
    {
        $user =  User::find()->where(['email' => $this->email])->one();

        return ($user && $user->confirmed == User::STATUS_CONFIRMED);
    }

    const SCENARIO_REGISTER = 'register';
    const SCENARIO_CONFIRM = 'confirm';

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        //$scenarios[ self::SCENARIO_REGISTER] = ['email', 'pass', 'age', 'login', 'sex', 'reCaptchaLogin']; //asd
        //$scenarios[ self::SCENARIO_CONFIRM] = ['email', 'pass', 'age', 'login', 'sex', 'reCaptchaLogin']; //asd

        $scenarios[ self::SCENARIO_REGISTER] = ['email', 'pass', 'age', 'login', 'sex']; // asd
        $scenarios[ self::SCENARIO_CONFIRM] = ['email', 'pass', 'age', 'login', 'sex']; // asd

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
  //          [['reCaptcha'], ReCaptchaValidator::class, 'uncheckedMessage' => 'Пожалуйста подтвердите что то вы не робот'],
            [['email'], 'required'],
            [['email'], 'email'],
            [['age'], 'safe'],
            [['age'], 'date'],
            [['auth_key'], 'safe'],
            [['login', 'name', 'second_name'], 'string', 'max' => 100],
            [['pass'], 'string', 'max' => 150],
            [['sex'], 'string', 'max' => 10],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique',  'message' => 'Данный e-mail занят',  'on' => self::SCENARIO_REGISTER],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'login' => 'Логин',
            'pass' => 'Пароль',
            'name' => 'Имя',
            'second_name' => 'Фамилия',
            'sex' => 'Пол',
            'age' => 'Дата рождения',
            'email' => 'Email',
        ];
    }





}
