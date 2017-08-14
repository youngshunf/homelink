<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_register".
 *
 * @property integer $register_id
 * @property string $user_guid
 * @property integer $activity_id
 * @property string $work_number
 * @property string $name
 * @property string $mobile
 * @property string $sign_shop
 * @property string $email
 * @property string $created_at
 */
class ActivityRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_register';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['work_number','name','mobile'], 'required'],
/*             ['email','email'], */
//             ['mobile','match','pattern'=>'^[1][3-8]+\\d{9}$^','message'=>'请输入正确的手机号码'],
//             [['mobile'], 'string','max'=>11, 'min'=>11, 'tooLong'=>'手机号不能大于11位', 'tooShort'=>'手机号不能小于11位'],            
        
//             [['user_guid'], 'string', 'max' => 48],
//             [['work_number', 'email'], 'string', 'max' => 32],
//             [['name'], 'string', 'max' => 64],
//             [['mobile'], 'string', 'max' => 20],
//             [['sign_shop'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'register_id' => 'Register ID',
            'user_guid' => 'User Guid',
            'activity_id' => '活动ID',
            'work_number' => '工号',
            'name' => '姓名',
            'mobile' => '电话',
            'weixin'=>'微信',
            'sign_shop' => '报名店面',
            'email' => '邮箱',
            'created_at' => '报名时间',
        ];
    }
    public function getActivity(){
        return $this->hasOne(Activity::className(), ['activity_id'=>'activity_id']);
    }
    public function getManager(){
        return $this->hasOne(User::className(), ['user_guid'=>'sign_manager']);
    }
}
