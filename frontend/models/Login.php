<?php
namespace frontend\models;


use yii;
use yii\base\Model;
use common\models\User;
use common\models\CommonUtil;
/**
 * Login form
 */
class Login extends Model
{
    
    public static function OpenIDLogin($openid){   
        $user=User::findOne(['openid'=>$openid]);             
        if( $user===null){
             
     /*        $access_token=WeChat::getAccessToken();
     
            $userInfo=WeChat::getUserInfo($access_token, $openid);
        
            $userInfo=json_decode($userInfo,true);
            
            if(!empty($userInfo['errcode'])){
                return false;
            } */
       
            $model=new User();             
            $model->user_guid=CommonUtil::createUuid();
            $model->openid=$openid;
         //处理昵称表情符号       
/*             $nick=$userInfo['nickname'];
			$nick = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return "";}, $nick);
            $model->nick=mysql_escape_string($nick);            
            $model->sex=$userInfo['sex'];
            $model->city=mysql_real_escape_string($userInfo['city']);
            $model->province=mysql_real_escape_string($userInfo['province']);
            $model->country=mysql_real_escape_string($userInfo['country']);
            $model->img_path=$userInfo['headimgurl'];
            $model->subscribe_time=$userInfo['subscribe_time']; */
             $model->created_at=time();
            if($model->save()){
                $user =$model;
            }else{                               
                return false;       
            } 
        }
     
        if(!empty($user)){         
            $_SESSION['user']=$user; 
            yii::$app->getSession()->set('user', $user);              
            return true;
        }
        
        return false;
    }
   

}
