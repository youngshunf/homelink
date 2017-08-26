<?php
namespace frontend\models;


use yii;
use yii\base\Model;
use common\models\User;
use common\models\CommonUtil;
use common\models\WeChat;
use common\models\AuthUser;
use yii\helpers\Json;
/**
 * 服务号登录
 */
class WechatLoginForm extends Model
{
    
    public function Login($code){
     
        $res=WeChat::getAccessTokenAndOpenid($code);  
        $res=json_decode($res,true);
        if(!empty($res['errcode'])){
            return false;
        }
        
        $access_token=$res['access_token'];
    
        $openid=$res['openid'];
        
        $model=User::findOne(['openid'=>$openid]);
        if(!empty($model)){
            if( Yii::$app->user->login($model,3600 * 24)){
                $_SESSION['user']=json_decode(Json::encode($model),true);
                if($model->is_auth==1){
                    if(empty($model->big_district)){
                        $authUser=AuthUser::findOne(['work_number'=>$model->work_number]);
                        if(!empty($authUser)){
                            $model->big_district=@$authUser->big_district;
                            $model->business_district=@$authUser->business_district;
                            $model->shop=@$authUser->shop;
                            $model->save();
                            return true;
                        }
                    }
                    return true;
                }
                return true;
            }else{
                return false;
            }      
        }
        
        $model=$this->Register($access_token,$openid);
        if($model){
            if(Yii::$app->user->login($model,3600 * 24)){
                $_SESSION['user']=json_decode(Json::encode($model),true);
                return true;
            }
                   
        }   
             
        return false;
    }
    
    public function Register($access_token,$openid){
          
        $userInfo=WeChat::getUserReturn($access_token, $openid);  
     
        $userInfo=json_decode($userInfo,true);        
        if(!empty($userInfo['errcode'])){
        return false;
        } 
         
        $model=new User();
        $model->user_guid=CommonUtil::createUuid();
        $model->openid=$openid;
        //处理昵称表情符号
             $nick=$userInfo['nickname'];
         $nick = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return "";}, $nick);
         $model->nick=$nick;
         $model->sex=$userInfo['sex'];
         $model->city=$userInfo['city'];
         $model->province=$userInfo['province'];
         $model->country=$userInfo['country'];
         $model->img_path=$userInfo['headimgurl'];
        // $model->subscribe_time=$userInfo['subscribe_time']; 
         $model->created_at=time();
        if($model->save()){
           return $model;
        }
        
        return false;
    }
    
   

}
