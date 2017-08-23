<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use backend\models\AdminUser;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $openid
 * @property string $user_guid
 * @property string $username
 * @property string $access_token
 * @property string $auth_key
 * @property string $password
 * @property integer $role_id
 * @property string $real_name
 * @property string $work_number
 * @property string $nick
 * @property integer $sex
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $address
 * @property string $big_district
 * @property string $business_district
 * @property integer $is_auth
 * @property string $path
 * @property string $photo
 * @property string $img_path
 * @property string $mobile
 * @property integer $mobile_verify
 * @property string $email
 * @property string $district
 * @property string $business_circle
 * @property string $building
 * @property string $shop
 * @property integer $email_verify
 * @property integer $isenable
 * @property string $sign
 * @property string $subscribe_time
 * @property string $created_at
 * @property string $updated_at
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'sex','pid', 'created_at','age', 'updated_at'], 'integer'],
            [['sign','birthday','talent','real_name'], 'string'],
            [['password', 'email','mobile'], 'string', 'max' => 48],
            [['username', 'access_token', 'nick', 'district', 'business_circle', 'building', 'shop'], 'string'],
            [['work_number'], 'string', 'max' => 32],
            [['city', 'province', 'country', 'address', 'big_district', 'business_district', 'path', 'photo'], 'string', 'max' => 128],
            [['img_path'], 'string', 'max' => 256],
            [['mobile'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'openid' => '微信id',
            'user_guid' => '用户GUID',
            'username' => '用户名',
            'access_token' => 'Access Token',
            'auth_key' => 'auth_key',
            'password' => '密码',
            'role_id' => '用户角色',
            'real_name' => '姓名',
            'work_number' => '工号',
            'nick' => '昵称',
            'sex' => '性别',
            'city' => '城市',
            'age'=>'年龄',
            'province' => '份省',
            'country' => '国家',
            'address' => '地址',
            'pid'=>'城市公司',
            'big_district' => '运营管理大区',
            'business_district' => '业务大区',
            'is_auth' => '是否验证',
            'path' => '头像',
            'photo' => 'Photo',
            'img_path' => '微信头像',
            'mobile' => '手机',
            'mobile_verify' => '手机验证:0-未验证;1-已验证',
            'email' => '邮箱',
            'district' => '大区',
            'business_circle' => '负责商圈',
            'building' => '负责楼盘',
            'shop' => '店面',
            'email_verify' => '邮箱验证:0-未验证;1-已验证',
            'isenable' => '账号是否启用:0-禁用;1-启用',
            'sign' => '个人简介',
            'subscribe_time' => '关注时间',
            'created_at' => '创建时间',
            'talent'=>'储备人才',
            'birthday'=>'出生日期',
            'updated_at' => '更新时间',
        ];
    }
    
    public function getGroup(){
        return $this->hasOne(UserGroup::className(), ['id'=>'group_id']);
    }
    
    public function getPuser(){
        return $this->hasOne(AdminUser::className(), ['id'=>'pid']);
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
    
    public static function findByOpenid($openid){
        return static::find()->andWhere(['openid'=>$openid])->one();
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
