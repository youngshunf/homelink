<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = $model->title;
$this->registerJsFile('@web/js/jquery-1.8.3.min.js');
$this->registerJsFile('@web/js/jquery.fancybox.js');
$this->registerCssFile('@web/css/jquery.fancybox.css');
?>
<style>

.content {
 
  padding: 8px;
  background: rgba(255, 255, 255, 1);
}
.wrap > .container {
  padding: 0;
}
img{
	display: block;
	max-width:100%;
	height:auto;
}
</style>
<div class="content">
  <h5>您已经报过名了</h5>
   <p><label class="label-control">工号:</label><?= $registerModel->work_number?></p>
   <p><label class="label-control">姓名:</label><?= $registerModel->name?></p>
   <p><label class="label-control">手机:</label><?= $registerModel->mobile?></p>
   <?php if($model->type!=3){?>
   <p><label class="label-control">邮箱:</label><?= $registerModel->email?></p>
   <p><label class="label-control">微信:</label><?= $registerModel->weixin?></p>
   <?php }?>
  <?php if($model->type==1){?>
  <p><label class="label-control">竞聘店面:</label><?= $registerModel->sign_shop?></p>
  <?php }?>
  
  <?php if($model->type==3){?>
  <p><label class="label-control">大区:</label><?= $registerModel->district_name?></p>
  <?php }?>
 
  <?php if($model->type==3333){?>
  <div class="center">
  <label class="label-control"> 请让签到管理员扫码二维码进行签到</label>
  <a class="fancybox"  title="扫描二维码签到"  data-fancybox-group="gallery"  href="<?= yii::getAlias('@photo')?>/qrcode/sign/<?=$registerModel->sign_qrcode?>">
            <img alt="签到二维码" src="<?= yii::getAlias('@photo')?>/qrcode/sign/<?=$registerModel->sign_qrcode?>" class="img-responsive">
    </a>   
  </div>
  <?php }?>
  
  <h5>活动详情</h5>
  <div class="c_img">
            <img alt="<?= $model->title?>" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
            <div class="c_words">
            <?=$model['title']?>
            </div>
    </div>
<h5>活动信息</h5>

  <p>【活动时间】<?= date("Y年m月d日",$model->start_time)?> <?= date("H:i",$model->start_time).'-'.date("H:i",$model->end_time)?></p>
    <p>【报名截止】<?= date("Y年m月d日 H:i",$model->sign_end_time)?></p>
    <p>【地点】<?= $model->address?></p>
   <h3>活动介绍</h3>
   <?= $model->content?>
   
 
   
</div>
<script>
$(document).ready(function(){
	$('.fancybox').fancybox({
		closeClick : true,
	});
});
</script>
