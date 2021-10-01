<?php

namespace app\modules\artlist\models\user;

use himiklab\yii2\recaptcha\ReCaptchaValidator;

use app\models\user\UserVisits;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;
    public  $reCaptchaLogin;


    const SCENARIO_LOGIN = 'login';
    const SCENARIO_CONFIRM = 'confirm';

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        //$scenarios[ self::SCENARIO_LOGIN] = ['username', 'password', 'reCaptchaLogin'];
        //$scenarios[ self::SCENARIO_CONFIRM] = ['username', 'password', 'reCaptchaLogin'];

        return $scenarios;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
 //           [['reCaptchaLogin'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(), 'uncheckedMessage' => 'Пожалуйста подтвердите что то вы не робот'],
            [['username', 'password'], 'required'],
            [['username'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['username' => 'email']],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            //['password', 'validatePassword', 'on' => self::SCENARIO_LOGIN],
                       // password is validated by validatePassword()


        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Не верный логин или пароль.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $userVisit = new UserVisits();
            $userVisit->user_id=Yii::$app->user->id;
            $userVisit->ip=Yii::$app->request->getUserIP();
            $userVisit->save();
            return Yii::$app->user->login($this->getUser(), 3600*24*30);
        }
        return false;
    }

    public function checkImport()
    {
        return User::find()->where(['email' => $this->username, 'confirmed' => User::STATUS_CONFIRMED])->exists();
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
