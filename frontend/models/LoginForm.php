<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

use common\models\CommonUtil;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    
    private $_user = false;
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, '用户名或密码错误.');
            }
//             else{
//                 $login_time = time();
//                 $login_ip = CommonUtil::getClientIp();
//                 User::updateAll(array('last_ip'=>$login_ip,'last_time'=>$login_time),array('user_guid'=>$user['user_guid']));
                
//             }
        }
    }
    
    public function attributeLabels()
    {
        return [
            'password2' => '确认密码',
            'password' => '密码',
            'mobile' => '手机号',
            'email' => '邮箱',
        ];
    }
    
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username,md5($this->password));
        }
        
        return $this->_user;
    }
}
