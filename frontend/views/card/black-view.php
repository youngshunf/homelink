<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Card */

$this->title = $model->name.'的名片';

$this->registerJsFile('@web/js/jquery-1.8.3.min.js');
$this->registerJsFile('@web/js/jquery.fancybox.js');
$this->registerCssFile('@web/css/jquery.fancybox.css');
$this->registerCssFile('@web/raty/jquery.raty.css');
$this->registerJsFile('@web/raty/jquery.raty.js');

?>
<style>
html,body{
	background:url(../img/black-bg.jpg);
	background-size:100%;
}
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
  position: relative;
  min-height: 1px;
  padding-right: 8px;
  padding-left: 8px;
}

.card-detail{
	margin-left:20px;
}
a{
	color:#fff;
}
.card-container .glyphicon{
	color:#fff;
}
.card-container {
	color:#fff;
}
.card-img {
  position: relative;
  margin: 0 auto;
  text-align: center;
  width: 300px;
  height: auto;
  line-height: 150px;
  margin-top: 28%;
}
.small-qrcode {
  color: #FFF;

}
.footer p {
  color: #fff;
}
.card-container {
   border-left: none;
  padding-left: 12px;
}
.card-img .img-circle {
  width: 150px;
}
</style>

  <div class="row">
    <div class="col-xs-7">
       
 </div>
    <div class="col-xs-5">
        <div class="card-container">
            <p><span class="glyphicon glyphicon-user" ></span>姓名: <?=$model->name?></p>
            <p><a href="tel:<?=$model->mobile?>"><span class="glyphicon glyphicon-earphone"></span>电话: <?=$model->mobile?></a></p>
            <p><span class="glyphicon glyphicon-globe"></span>大区: <?=$model->district?></p>
            
            
           
        </div>
    
    </div>
    </div>
    
    <div class="row">
        <div class="col-xs-12">
         <div class="card-img">
          <?php if(!empty($model->photo)){?>        
            <img alt="头像" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive img-circle">
          <?php }else{?>
           <img alt="头像" src="<?= yii::getAlias('@avatar')?>/unknown.jpg" class="img-responsive img-circle">
          <?php }?>
          <a class="fancybox"  title="扫描二维码访问<?=$model->name?>名片"  data-fancybox-group="gallery"  href="<?= yii::getAlias('@photo')?>/qrcode/<?=$model->user_guid?>.png">
            <span class="glyphicon glyphicon-qrcode small-qrcode" > </span>
            </a>   
            
        </div>
      
            <div class="card-container card-detail" >
             <p><span class="glyphicon glyphicon-envelope"></span>邮箱: <?=$model->email?></p>
            <p><span class="glyphicon glyphicon-map-marker"></span>地址: <?=$model->address?></p>
            <p><span class="glyphicon glyphicon-tag "></span>店面: <?=$model->shop?></p>
            <p><span  class="glyphicon glyphicon-record"></span>负责商圈: <?=$model->business_circle?></p>
            <p><span class="glyphicon glyphicon-home "></span>负责楼盘:<?=$model->building?></p>
            <p><span class="glyphicon glyphicon-list"></span>个人简介: <?=$model->sign?></p>
            <p><span class="glyphicon glyphicon-star"></span>好评: <span id="raty-score" class="center"></span></p>
            </div>
        </div>
    </div>
    <?php 
    $user=yii::$app->getSession()->get('user');
     if(!yii::$app->user->isGuest&&$model->user_guid==yii::$app->user->identity->user_guid){?>
    <div class="card-edit center">
            <a class="btn btn-info" href="<?= Url::to(['update','id'=>$model->card_id])?>">修改名片</a>         
    </div>
    <?php }?>
    
    <div class="comment center">
        <a class="btn btn-warning" href="<?= Url::to(['comment','id'=>$model->card_id])?>"> 评论</a>
    </div>
    


    
<!--<audio id="bgm" src="http://jiangsu.sinaimg.cn/zt/s/yxqxwspds/mobile/bgmcut.mp3" autoplay="autoplay" loop="" style="display: none; width: 0; height: 0;"></audio>  -->

<!-- BGM play/mut Btn -->
<style>
.bgm-btn{ position:absolute; top: 16px; right:18px; width: 30px; height: 30px; background-size: 100%; background-image: url("http://s3img.city.sina.com.cn/activity/common/large/9f9acca088ab3e67f12eb612bdd12c07.png"); z-index: 1; -webkit-animation: myrotate 5s linear 0s infinite normal;}
.bgm-btn.mut{ background-position: 0px -30px; -webkit-animation: none;}

@-webkit-keyframes myrotate /*Safari and Chrome*/
{
0% { -webkit-transform:rotate(0deg);}
25% { -webkit-transform:rotate(-90deg);}	
50% { -webkit-transform:rotate(-180deg);}
75% { -webkit-transform:rotate(-270deg);}
100% { -webkit-transform:rotate(-360deg);}
}

</style>
<!-- <div class="bgm-btn"></div> -->


<!-- Touch to play BGM. JS -->
<script type="text/javascript">
/* var firstTouch = true;
$('body').bind("touchstart",function(e){
	if ( firstTouch ) {
		firstTouch = false;
		document.getElementById('bgm').play();
	}else{
		return;
	}
});

$(".bgm-btn").bind("touchstart",function(e){  
	//e.preventDefault();
	//e.stopPropagation();
	var dom = document.getElementById('bgm');
	if( dom.paused ){
		dom.play();
		$(".bgm-btn").removeClass("mut");
	}else{
		dom.pause();
		$(".bgm-btn").addClass("mut");
	}
}); */

</script>

<script>
$(document).ready(function(){
	$('.fancybox').fancybox({
		closeClick : true,
	});

	$.fn.raty.defaults.path = '../raty/images';
	 $("#raty-score").raty({ readOnly: true, score: <?= empty($score)?0:$score?> });
	


});

</script>