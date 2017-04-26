<?php

namespace common\models;
use yii;
/**
 * 微信发送模板消息
 * @author youngshunf
 *
 */

class WeChatTemplate{
	public $appid;
	public $appsecret;
	public $access_token;
	
	public function __construct($appid,$appsecret){
	    $this->appid=$appid;
	    $this->appsecret=$appsecret;
	    $this->access_token=$this->getLocalToken();
	}
	
	public  function getLocalToken(){		
	    $fp=fopen('wechat_access_token.txt', 'a');
	    $tokenInfo=fread($fp, 1000);
	    fclose($fp);
	    $jsonInfo=json_decode($tokenInfo,true);
	    if(empty($jsonInfo)){
	        $jsonInfo=$this->getAccesssToken();
	    }elseif(time()>=$jsonInfo['expire_time']){
	        $jsonInfo=$this->getAccesssToken();
	    }
	    
	 return $jsonInfo['access_token'];
	}

	public  function getAccesssToken(){
	    	   
	    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;	    	  
	    $result =$this->https_request($url);
	    $jsoninfo=json_decode($result,true);
	    if(!empty($jsoninfo['errcode'])){
	        $fp=fopen('wechat_error.log', 'a+');
	        fwrite($fp, $result);
	        fclose($fp);	     
	    }else{
	        $fp=fopen('wechat_access_token.txt', 'w');
	        $data=[
	            'access_token'=>$jsoninfo['access_token'],
	            'expire_time'=>time()+7000,
	        ];
	        fwrite($fp, json_encode($data));
	        fclose($fp);	    
	    }
	    return $jsoninfo;	
	}
	
	public function send_template_message($data){
	    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->access_token;
	    $res=$this->https_request($url,json_encode($data));
	    return json_decode($res,true);
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
	
	
	
}