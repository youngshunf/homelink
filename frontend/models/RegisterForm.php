<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\CommonUtil;


/**
 * Register form
 */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $mobile;
    public $password;
    public $password2;
    public $agree_rules;
    private $_user = false;


    public function rules()
    {
        return [
          
            [['username', 'password','password2',], 'required'],    
          // 邮箱验证
            ['email', 'email'],
            ['agree_rules','compare', 'compareValue' => true,'message'=>"请同意条款"],
            //验证手机号
            ['mobile','match','pattern'=>'^[1][3-8]+\\d{9}$^','message'=>'请输入正确的手机号码'], 
            [['mobile'], 'string','max'=>11, 'min'=>11, 'tooLong'=>'手机号不能大于11位', 'tooShort'=>'手机号不能小于11位'],           
            ['mobile', 'unique','targetClass' => '\common\models\User', 'message'=>'该手机已注册'],            
           //验证邮箱是否注册
            ['email', 'unique','targetClass' => '\common\models\User', 'message'=>'邮箱已存在'], 
            // 验证两次输入的密码是否一致
            ['password2', 'compare', 'compareAttribute'=>'password','message'=>'两次密码不一致'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password2' => '确认密码',
            'password' => '密码',       
            'mobile' => '手机号',         
            'email' => '邮箱',       
        ];
    }

    /**
     * 注册
     *
     */
    public function register()
    {
        	$user= new User();    
        	$user->username=$this->username;
        	$user->mobile=$this->mobile;
        	$user->user_guid=CommonUtil::createUuid();
        	$user->openid=$user->user_guid;
        	$user->email=$this->email;
//         	$user->setPassword($this->password);
        	$user->generateAuthKey();
        	$user->created_at=time();
        	$user->password=md5($this->password);
           	if($user->save()){
           	    return true;
           	}
           	
           	return false;
        				
    }

}
