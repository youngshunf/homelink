<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Register form
 */
class ResetForm extends Model
{
    public $password;
    public $password2;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          
            [['password','password2'], 'required','message'=>'不能为空'],
            // 验证两次输入的密码是否一致
            ['password2', 'compare', 'compareAttribute'=>'password','message'=>'两次密码不一致'],
        ];
    }

    /**
     * 重置密码
     *
     */
    public function reset($user_guid)
    {
    	$password = md5($this->password);
    	if(User::updateAll(["password"=>$password],"user_guid='$user_guid'")){
    		return true;
		}       	     	       	
       	return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username,$this->password);
        }

        return $this->_user;
    }
}
