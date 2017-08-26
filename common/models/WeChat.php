<?php

namespace common\models;
use yii;
/**
 * 微信认证服务号获取用户信息
 * @author youngshunf
 *
 */

class WeChat{
	
	public static function getUserReturn($access_token,$openid)
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

	private static  function httpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
	 
	    curl_close($curl);	
	    return $res;
	}
	
	public static function getAccessTokenAndOpenid($code){
	    $appid=yii::$app->params['appid'];
	    $appsecret=yii::$app->params['appsecret'];
	    $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
	    return self::httpGet($url);
	}
	
	
}