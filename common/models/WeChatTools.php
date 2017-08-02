<?php

namespace common\models;
use yii;
use yii\grid\DataColumn;
/**
 * 与微信服务器交互的工具类
 * @author youngshunf
 *
 */
class WeChatTools{
	
    //授权登录code获取access_token和openid
    public static function getAccessTokenAndOpenidByCode($code){
        $appid=yii::$app->params['appid'];
        $appsecret=yii::$app->params['appsecret'];
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        return self::httpGet($url);
    }
    
    //授权登录获取用户信息
	public static function getAuthUserInfo($access_token,$openid)
	{
		$url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);		
		curl_close($ch); 				
		return $output;
	}

	//使用GET方法请求数据
	public static  function httpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 5000);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
	    curl_close($curl);	
	    return $res;
	}
	
	//支持GET和POST请求数据
	public static  function httpsRequest($url,$data = null){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
// 	    $this_header = array(
// 	        "content-type: application/x-www-form-urlencoded;
//             charset=UTF-8"
// 	    );
// 	    curl_setopt($curl,CURLOPT_HTTPHEADER,$this_header);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 5000);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}
	
	//通过appid和appsecret获取access_token
	public static function getAccessToken($appid,$appsecret){
	    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    $jsoninfo = json_decode($output, true);
	    
	    //获取access_token失败
	    if(!empty($jsoninfo['errcode'])){
	       return false;
	    }
	    	    
	    return $jsoninfo;
	  }
	
	  //验证当前使用的公众号access_token是否过期
	  public static function validateCurrentAccessToken(){
	      $appInfo=yii::$app->getSession()->get('appinfo');
	      //未过期,直接返回
	      if(!empty($appInfo['access_token'])&&$appInfo['expire_time']>time()){
	          return true;
	      }
	      
	      $result=self::getAccessToken($appInfo['appid'], $appInfo['appsecret']);
	    
	      //获取失败
	      if(!$result){
	          return false;
	      }
	      //更新数据库中access_token
	      $model=Appinfo::findOne(['appid'=>$appInfo['appid'],'admin_user'=>$appInfo['admin_user'],'appinfo_id'=>$appInfo['id']]);
            if(empty($model)){
                return false;
            }
	      $model->access_token=$result['access_token'];
	      $model->expire_time=time()+3600;
	      $model->updated_at=time();
	      if($model->save()){
	          yii::$app->getSession()->set('appinfo', $model);
	          return true;
	      }
	      return false;
	  }
	  
	  //验证指定的公众号access_token是否过期
	  public static function validateAccessToken($appid,$appinfo_id){
	      $appInfo=Appinfo::findOne(['id'=>$appinfo_id,'appid'=>$appid]);
	      //未过期,直接返回
	      if(!empty($appInfo['access_token'])&&$appInfo['expire_time']>time()){
	          return $appInfo;
	      }
	       
	      $result=self::getAccessToken($appInfo['appid'], $appInfo['appsecret']);
	      //获取失败
	      if(!$result){
	          return false;
	      }
	      //更新数据库中access_token
	      $appInfo->access_token=$result['access_token'];
	      $appInfo->expire_time=time()+3600;
	      $appInfo->updated_at=time();
	      if($appInfo->save()){
	          return $appInfo;
	      }
	      return false;
	  }
	
	  //查询所有分组
	  public static function getAllGroup($access_token){
	      $url="https://api.weixin.qq.com/cgi-bin/groups/get?access_token=".$access_token;
	      return self::httpGet($url);
	  }
	  
	  //创建分组
	  public static function createGroup($access_token,$data){
	     $url="https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$access_token;
	     return self::httpsRequest($url,$data);
	  }
	  
	  //删除用户分组
	  public static function deleteGroup($access_token,$data){
	      $url="https://api.weixin.qq.com/cgi-bin/groups/delete?access_token=".$access_token;
	      return self::httpsRequest($url,$data);
	  }
	  
	  //创建菜单
	  public static function createMenu($access_token,$data){
	      $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
	      return self::httpsRequest($url,$data);
	  }
	  
	  //创建个性化菜单
	  public static function createConditionalMenu($access_token,$data){
	      $url="https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=".$access_token;
	      return self::httpsRequest($url,$data);
	  }
	  
	  //通过openid获取用户信息
	  	public static function getUserInfo($access_token,$openid)
	{
		$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
	    return self::httpGet($url);
	}
	
	//移动用户分组
	public static function moveUserGroup($access_token,$data){
	    $url="https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$access_token;
	    return self::httpsRequest($url,$data);
	}
	
	
	//生成场景二维码
	public static function getQrcode($access_token,$param,$type){
	    $scenen_id=$param;
	    //临时
	    if($type==1){
	        $qrcode=[
	            'expire_seconds'=>604800,
	            'action_name'=>'QR_SCENE',
	            'action_info'=>[
	                'scene'=>[
	                'scene_id'=>$scenen_id
	            ]
	        ]
	        ];
	    }elseif ($type==2){
	    //永久
        	    $qrcode=[
        	        'action_name'=>'QR_LIMIT_SCENE',
        	        'action_info'=>[
        	            'scene'=>[
        					'scene_id'=>$scenen_id
        	               ]
        	            ]
        	        ];
	         }
	        $qrcode=json_encode($qrcode);
	        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
	        $result=self::httpsRequest($url,$qrcode);
            
	        $jsoninfo=json_decode($result,true);
    	    if(!empty($jsoninfo['errcode'])){
    	           return $jsoninfo;
    	       }
	       	$ticket=$jsoninfo['ticket'];
	       //获取二维码图片,下载到本地
	       	$url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
		      $qrdata=self::httpGet($url);
		     $basePath="../../upload/qrcode/";
			$path=date("Ymd").'/';
			if(!is_dir($basePath.$path)){
			    mkdir($basePath.$path);
	           }
		  $filename=date("YmdHis").rand(1000, 9999).'.jpg';
			$localfile=fopen($basePath.$path.$filename, 'w');
			if(false!==$localfile){
			    if(false!==fwrite($localfile, $qrdata)){
			    return $qrcode=[
			           'path'=>$path,
						'photo'=>$filename
					];
				}
			}
			return false;
		}
	  
		public static function sendMessage($access_token,$data){
		    $url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$access_token;
		    return self::httpsRequest($url,$data);
		}
	
}