<?php
namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $user_guid
 * @property string $username 
 * @property integer $role_id
 * @property string $password
 * @property string $auth_key
 * @property integer $register_type
 * @property integer $rank
 * @property integer $user_level
 * @property integer $generation
 * @property string $salt
 * @property string $ID_code
 * @property string $real_name
 * @property string $nick
 * @property string $gender
 * @property string $img_path
 * @property string $last_ip
 * @property string $last_time
 * @property string $mobile
 * @property integer $mobile_verify
 * @property string $mobile_bind
 * @property string $email
 * @property integer $email_verify
 * @property string $email_bind
 * @property string $active_code
 * @property integer $enable
 * @property string $pw_question
 * @property string $pw_answer
 */
class AdminUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['username','password2','password','role_id'], 'required'],
            [['real_name', 'nick','sex','mobile','email'], 'safe'],
            ['username', 'unique', 'message'=>'用户名已存在','on'=>['manager_create']],
            // 验证两次输入的密码是否一致
            ['password2', 'compare', 'compareAttribute'=>'password','message'=>'两次密码不一致'],
        ];
    }
	
    public function attributeLabels()
    {
    	return [
    	'username'=>'用户名',
    	 'real_name'=>'姓名',
    	  'last_ip'=>'上次登录IP',
    	   'last_time'=>'上次登录时间',
    	   'role_id'=>'用户角色',
    	    'nick'=>'昵称',
    	    'sex'=>'性别',
    	    'mobile'=>'手机',
    	    'eamil'=>'邮箱',
    	'password'=>'密码'
    	];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username,$password)
    {
    	return static::find()->where("password='$password' AND(username='$username' OR email='$username' OR mobile='$username')")
    	->one();
       // return static::findOne(['username' => $username,'password'=>md5($password)]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
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
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
