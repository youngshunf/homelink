<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\CommonUtil;
use common\models\User;
use common\models\AuthUser;
/**
 * Login form
 */
class AuthForm extends Model
{
    public $real_name;
    public $work_number;
    public $mobile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['real_name', 'work_number','mobile'], 'required'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'real_name' => '姓名',
            'work_number'=>'工号',
            'mobile'=>'手机号'
        ];
    }


    public function AuthUser()
    {
        if(!$this->validate()){
            return false;
        }
        $authUser=AuthUser::findOne(['work_number'=>$this->work_number]);
        if(empty($authUser)){
            yii::$app->getSession()->setFlash('error','绑定身份失败,请确认您输入的工号是否正确.');
            return false;
        }
        $wUser=User::findOne(['work_number'=>$this->work_number]);
        if(!empty($wUser)){
            yii::$app->getSession()->setFlash('error','此工号已被绑定,工号不能被重复绑定');
            return false;
        }
        $use_guid=yii::$app->user->identity->user_guid;
        $user=User::findOne(['user_guid'=>$use_guid]);
        $user->real_name=$this->real_name;
        $user->work_number=$this->work_number;
        $user->mobile=$this->mobile;
        $user->role_id=CommonUtil::getRoleId($authUser->role_name);
        $user->big_district=@$authUser->big_district;
        $user->business_district=@$authUser->business_district;
        $user->pid=$authUser->pid;
        $user->shop=@$authUser->shop;
        $user->is_auth=1;
        $user->updated_at=time();
        if($user->save()){          
            return true;
        }
        return false;
    }
}
