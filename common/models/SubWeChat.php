<?php

namespace common\models;
use yii;
/**
 * 微信订阅号和未认证服务号获取用户信息
 * @author youngshunf
 *
 */

class SubWeChat{
	const  APPID="wx32f12b3b26f76e2b";
	const  APPSECRET="8ed6f9bfd303b48d738660f217d4fe60";
	
	public static function getAccessToken(){		
	    $fp=fopen(yii::getAlias('@frontend').'/runtime/logs/wechat_access_token.txt', 'r');
	    $tokenInfo=fread($fp, 1000);
	    fclose($fp);
	    $jsonInfo=json_decode($tokenInfo,true);
	    if(empty($jsonInfo)){
	        $jsonInfo=self::getToken();
	    }
	 return $jsonInfo['access_token'];
	}
	//刷新token，每隔1小时刷新，console中调用
	public static function getToken(){
	    $appid = WeChat::APPID;
	    $appsecret = WeChat::APPSECRET;	    
	    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($ch);	    
	    curl_close($ch);
	    $jsoninfo = json_decode($output, true);
	    if(!empty($jsoninfo['errcode'])){
	        $fp=fopen(yii::getAlias('@frontend').'/runtime/logs/wechat_error.log', 'a+');
	        fwrite($fp, $output);
	        fclose($fp);
	     
	    }else{
	        $fp=fopen(yii::getAlias('@frontend').'/runtime/logs/wechat_access_token.txt', 'w');
	        fwrite($fp, $output);
	        fclose($fp);
	    
	    }
	    return $jsoninfo;	
	}
	
	public static function getUserReturn($access_token,$openid)
	{

		$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);		
		curl_close($ch); 				
		return $output;
	}
	
	public static function getUserInfo($access_token,$openid){
	    $output=self::getUserReturn($access_token, $openid);
	    $jsonInfo=json_decode($output,true);

	    if(!empty($jsonInfo['errcode'])){
	        switch ($jsonInfo['errcode']) {
	            //token超时,主动刷新token
	            case 42001:
	                self::getToken();
	                sleep(5);
	                $output=self::getUserReturn($access_token, $openid);
	                return $output;
	             default:break;
	        }
	    }
	    return $output;
	}
	
	
	public function https_request($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	
	private static  function httpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
	    curl_close($curl);	
	    return $res;
	}
	
	public static function getAccessTokenAndOpenid($code){
	    $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".WeChat::APPID."&redirect_uri=http://wish.mi2you.com/site/login-do&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
	    self::httpGet($url);
	}
	
	
}