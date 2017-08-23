<?php
namespace common\models;
/*
  微信接口
*/
define("TOKEN", "homelink");

class WechatCallbackApi
{
    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }

    //响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            //   $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
             
            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收事件消息
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = array();
                $content[] = array("Title"=>"欢迎关注链家优才", "Description"=>"如果您是链家员工，欢迎进行身份验证，与MVP共同成长，点此进行身份验证.", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/welcome.jpg", "Url" =>"http://mvp.homelink.com.cn/site/auth");
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "SCAN":
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "COMPANY":
                        $content = array();
                        $content[] = array("Title"=>"北京链家地产", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://www.lianjia.com/");
                        break;
                    case "登录":
                        $content = array();
                        $content[] = array("Title"=>"链家优才", "Description"=>"欢迎访问链家优才", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/welcome.jpg", "Url" =>"http://mvp.homelink.com.cn/site/auth?openid=".$object->FromUserName);
                        break;
                    case "MYACCOUNT":
                        $content = "回复‘21’, 进入身份验证
回复‘22’，进入我的名片
回复‘23’，进入意见反馈";
                        break;
                     case "INTERVIEW":
                        $content ="请输入面试者姓名或身份证号码查询:如高强";
                        break;

                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "MASSSENDJOBFINISH":
                $content = "消息ID：".$object->MsgID."，结果：".$object->Status."，粉丝数：".$object->TotalCount."，过滤：".$object->FilterCount."，发送成功：".$object->SentCount."，发送失败：".$object->ErrorCount;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }
        if(is_array($content)){
            if (isset($content[0])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }

        return $result;
    }
    
    public  function post($url, $data){//file_get_content
        $postdata = http_build_query(
            $data
        );
        $opts = array('http' =>
 
                      array(
 
                          'method'  => 'POST',
 
                          'header'  => 'Content-type: application/x-www-form-urlencoded',
 
                          'content' => $postdata
                      )
 
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
 
        return $result;
 
    }
    
    private  function getInterviewResult($idNo){
        $url="http://eagleeye.yisier.com/ApiProvider!cxMsResult.svr";
        $secPwd='Y@c1Ai&Qx8';
        $md5Auth=md5($secPwd.date('Y-m-d').$idNo);
        $data=[
            'secPwd'=>$secPwd,
            'idNo'=>$idNo,
            'md5Auth'=>$md5Auth
        ];
        $result=$this->post($url,$data);
        return json_decode($result,true);
    }
    
    private function  getPassStat($state){
        $arr=[
            '0'=>'未查询到6个月内的面试',
            '1'=>'通过',
            '2'=>'未通过',
        ];
        return  $arr[$state];
    }
    //接收文本消息
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        //多客服人工回复模式
        if (strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗")){
            $result = $this->transmitService($object);
        }
        //自动回复模式
        else{
            if (strstr($keyword, "身份验证") || $keyword==21){
                $content = array();
                $content[] = array("Title"=>"请进行身份验证",  "Description"=>"点击此处进行身份验证,验证通过后即可使用链家优才的全部功能", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/welcome.jpg", "Url" =>"http://mvp.homelink.com.cn/site/auth?openid=".$object->FromUserName);
            }else if (strstr($keyword, "我的名片")||$keyword==22){
                $content = array();
                $content[] = array("Title"=>"欢迎使用我的名片", "Description"=>"第一次使用名片需要进行名片设置,设置成功后可将名片转发给好友或朋友圈.", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/card.jpg", "Url" =>"http://mvp.homelink.com.cn/card/create?openid=".$object->FromUserName);
                 
            }else if (strstr($keyword, "找人") || $keyword==3){
                $content = array();
                $content[] = array("Title"=>"欢迎使用找人功能", "Description"=>"点击此处找人,可按照大区,商圈,楼盘找人,并查看他的名片.", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/vote.jpg", "Url" =>"http://mvp.homelink.com.cn/card/index?openid=".$object->FromUserName);
            }else if (strstr($keyword, "我的数据")||$keyword==2){
                $content = array();
                $content[] = array("Title"=>"访问我的数据", "Description"=>"点击此处访问我的数据.", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/mydata1.jpg", "Url" =>"http://mvp.homelink.com.cn/data/my-data?openid=".$object->FromUserName);
            }else if (strstr($keyword, "数据查询")||$keyword==4){
                $content = array();
                $content[] = array("Title"=>"查询数据", "Description"=>"点击此处进行数据查询", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/query2.jpg", "Url" =>"http://mvp.homelink.com.cn/data/query-data?openid=".$object->FromUserName);
            }else if (strstr($keyword, "投票")){
                $content = array();
                $content[] = array("Title"=>"微信投票", "Description"=>"点击此处进行微信投票", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/vote1.jpg", "Url" =>"http://mvp.homelink.com.cn/vote?openid=".$object->FromUserName);
            } else if (strstr($keyword, "报名")){
                $content = array();
                $content[] = array("Title"=>"微信报名", "Description"=>"点击查看微信报名,多期活动可同时报名", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/baoming.jpg", "Url" =>"http://mvp.homelink.com.cn/activity/index?openid=".$object->FromUserName);
            }else if (strstr($keyword, "评价")){
                $content = array();
                $content[] = array("Title"=>"微信评价", "Description"=>"点击对自己的下级进行评价", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/eval.jpg", "Url" =>"http://mvp.homelink.com.cn/eval/index?openid=".$object->FromUserName);
            }else if (strstr($keyword, "问题反馈") || $keyword==23){
                $content = array();
                $content[] = array("Title"=>"问题反馈", "Description"=>"首先感谢您的使用,有任何问题请及时反馈，以便我们更好的改进,点击此处进行问题反馈.", "PicUrl"=>"http://loversshow-photo.stor.sinaapp.com/advice.jpg", "Url" =>"http://mvp.homelink.com.cn/advice/create?openid=".$object->FromUserName);
            }else{
                $content="";
                $interviewResult=InterviewResult::find()->andWhere(" name like '%$keyword%' or id_code = '$keyword' ")->limit(10)->all();
                
                    $i=1;
                    $result=$this->getInterviewResult($keyword);
                    if($result['code']=='1001'){
                        foreach ($result['results'] as $v){
                            $content .='
第'.($i++).'位:
姓名:'.@$v['srName'].';
身份证号:'.$this->truncateIdcode(@$v['idNo']).';
电话:'.@$v['srPhone'].';
推荐人系统号:'.@$v['reeBrokerId'].';
推荐人:'.@$v['reeName'].';
面试时间:'.@$v['msDate'].';
面试结果:'.@$v['passstat'].';
入职定级:'.@$v['apeStartLevel'].';
入职状态:'.@$v['entStat'].';
培训结果:'.@$v['trainResult'].';
<a href="'.@$v['detailUrl'].'">结果详情,点击查看</a>';
                        }
                    }
                    
                    foreach ($interviewResult as $k=>$v){
                        $content .='
第'.($i++).'位:
姓名:'.$v->name.';
身份证号:'.$this->truncateIdcode($v->id_code).';
入职定级:'.$v->level.';
推荐人系统号:'.$v->rec_work_number.';
推荐人:'.$v->rec_name.';
面试时间:'.$v->interview_time.';
面试结果:'.$v->interview_result.';
培训结果:'.$v->train_result.';
入职状态:'.$v->status.';
备注:'.$v->remark;
                    }
                    
                    if(empty($content)){
                        $content="面试记录查询无[$keyword]相关信息，可以点击相应菜单重新查询，回复准确身份证号码或姓名信息";
                    }
                    
                }
                
                

            if(is_array($content)){
                if (isset($content[0]['PicUrl'])){
                    $result = $this->transmitNews($object, $content);
                }else if (isset($content['MusicUrl'])){
                    $result = $this->transmitMusic($object, $content);
                }
            }else{
                $result = $this->transmitText($object, $content);
            }
        }

        return $result;
    }

    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }

    //接收视频消息
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
            <MediaId><![CDATA[%s]]></MediaId>
        </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[image]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[voice]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[video]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>%s</ArticleCount>
        <Articles>
        $item_str</Articles>
        </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[music]]></MsgType>
        $item_str
        </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }
    
    private function truncateIdcode($idCode){
        return substr($idCode, 0,10).'XXXX'.substr($idCode, 14,17);
    }

}
?>
