<?php
use common\models\CommonUtil;
use yii\helpers\Url;

?>
<!DOCTYPE html>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="description" content="">
<title><?=$model->title?></title>
<link href="<?= yii::getAlias('@web')?>/css/mobile_module.css" rel="stylesheet" type="text/css">
<link href="<?= yii::getAlias('@web')?>/css/vote.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= yii::getAlias('@web')?>/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
var cknums = 0;
var type = "0";
function checkForm(){
	<?php if($model->type==0){?>
     var val=$("input[type='radio']:checked").val();
     if(!val){
    	 $('#errorInfo').html('请选择一项再投票').show();
    	 return false;
     }else{
        return true;
     }
	<?php }else{?>

	var content = '';
	var msg = 0;
	var cknums=10;
	$("input[type='checkbox']:checked").each(function(){ msg += 1; });

	/* if(msg>cknums){
		$('#errorInfo').html('该投票最多可同时选择'+cknums+'项').show();
		return false;
	} */
	if(msg<1){
		$('#errorInfo').html('请至少选择一项再投票').show();
		return false;
	}

	return true;
	<?php }?>
}
</script>
</head>
<body>
<div class="container body">
	<div class="vote_wrap">
  <article>
  	<div class="img_wrap">
        
      <img width="100%" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>">
        	<h2><?=$model->title?></h2>
            </div>
    <div class="vote_info mb">
    <?= $model->content?>
</div>
    <p class="time">投票截止日期：<?= CommonUtil::fomatTime($model->end_time)?></p>  </article>
  <p class="vote_type">
      本次投票为<?= CommonUtil::getDescByValue('vote', 'type', $model->type)?>投票,已经有<?= $model->vote_number?>人投票.</p>                  
   <p class="vote_type">   <span class="gray">
   <?php if($model->start_time>time()){?>
    投票还未开始,投票开始时间:<?= CommonUtil::fomatTime($model->start_time)?>
    <?php }elseif($model->end_time<time()){?>
      投票已结束,不能再继续进行投票
    <?php }else{?>
          <?php if($isVote==0){?>
          赶紧投下你宝贵的一票吧 :)
          <?php }else{?>
           你已投过票了,不能再投了。
      <?php }}?></span></p>
                       
 
    
  <form id="form1" name="form1" method="post" action="<?= Url::to(['vote'])?>" onsubmit="return checkForm();">
    <div class="clearfix choice_list">
    <input type="hidden" name="voteid" value="<?= $model->vote_id?>">
      <ul>
      <?php foreach ($voteItem as $k=>$item){?>
        <li>
         <?php if(!empty($item->photo)){?>
           <p class="mb"><img src="<?= yii::getAlias('@photo').'/'.$item->path.$item->photo?>"></p>
           <?php }?>
            <p class="list">
            <?php if($model->type==0){?>
             <input type="radio" class="regular-radio" id="check_<?= $item->vote_item_id?>" name="optArr[]" value="<?= $item->vote_item_id?>"><label for="check_<?= $item->vote_item_id?>"></label><?= $item->content?>           </p>
           <?php }else{?>
                <input type="checkbox" class="regular-checkbox" id="check_<?= $item->vote_item_id?>" name="optArr[]" value="<?= $item->vote_item_id?>"><label for="check_<?= $item->vote_item_id?>"></label><?= $item->content?>           </p>           
           <?php }?>
            <div class="clearfix tb">
              <div class="databar">
                <div class="actual_data vote-per-<?= $k?>" style="width: 0%"></div>
              </div>
              <p class="count">
                <?= $item->vote_number?>票
              </p>
            </div>
          </li>
          <?php }?>
       </ul>
    </div>
    
    <div class="warning" id="errorInfo"></div>
    <input type="hidden" value="gh_0b2c8440439f" name="token">
    <input type="hidden" value="" name="wecha_id">
  <?php if($isVote==0&&$model->start_time<time()&&$model->end_time>time()){?>
        <div class="tb"><input type="submit" class="btn m_10 flex_1" value="确认提交"></div>
    <?php }?>         
    
     <div class="tb" id="close_page" style="<?php if($isVote==0){?>display:none <?php }?>"><input type="button" id="close_page_btn" class="btn m_10 flex_1" value="关闭返回"></div>
  </form>
  </div>
</div>


<script>
function init_close(){
		$('#close_page').show();
		$('#close_page_btn').click(function(){
			  WeixinJSBridge.invoke('closeWindow',{},function(res){;});
		});	
}
$(function(){
	
	<?php foreach ($voteItem as $k =>$v){?>
	$(".vote-per-<?= $k ?>").animate( { width: "<?= ($model->vote_number==0?0:$v->vote_number/$model->vote_number)*100  ?>%"}, 5000);
	<?php }?>

	$(".list").click(function () {
		 if ($(this).hasClass("bgBlue")) {
			 $(this).removeClass("bgBlue").find("input").attr("checked", true);
		 } else {
			 $(this).addClass("bgBlue").find("input").attr("checked", false);
		 }
	 });
	 
	if (typeof WeixinJSBridge == "undefined"){
		if( document.addEventListener ){
			document.addEventListener('WeixinJSBridgeReady', init_close, false);
		}else if (document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', init_close); 
			document.attachEvent('onWeixinJSBridgeReady', init_close);
		}
	}else{
		init_close();
	}	   
});
</script></body></html>