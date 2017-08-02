<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use backend;
use backend\models;
use common\models\CommonUtil;
use yii\helpers\Json;
use yii\web\Cookie;

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
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
	        if (!$user) {
	            $this->addError($attribute, '用户名或密码错误.');
	        }else{
	            if ($user['role_id'] == 99 || $user['role_id'] == 98 || $user['role_id'] == 89||$user['role_id'] == 88) {
	        		$login_time = time();
	        		$login_ip = CommonUtil::getClientIp();
	        		setcookie('user',$user['username'], time() + 86400);
	        		//echo $_COOKIE['user']=$user['username'];die;
	        		AdminUser::updateAll(array('last_ip'=>$login_ip,'last_time'=>$login_time),array('user_guid'=>$user['user_guid']));
            	}else{
            		$this->addError($attribute, '该用户没有登录权限.');
            	}
	        }
        }
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
            $this->_user = AdminUser::findByUsername($this->username,md5($this->password));
        }

        return $this->_user;
    }
}
