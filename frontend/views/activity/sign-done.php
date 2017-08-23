<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = "签到成功";
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
    <div >
    <br>
    <br>
        <h1 class="center" ><i class="icon-ok time"></i> 签到成功!</h1>
    <br>
        <p class="time"> 【签到时间】 <?= CommonUtil::fomatTime($registerModel->sign_time)?></p>
    </div>

  <h5>报名信息</h5>
   <p><label class="label-control">工号:</label> <?= $registerModel->work_number?></p>
   <p><label class="label-control">姓名:</label> <?= $registerModel->name?></p>
   <p><label class="label-control">手机:</label> <?= $registerModel->mobile?></p>
  <?php if($model->type==1){?>
  <p><label class="label-control">竞聘店面: </label><?= $registerModel->sign_shop?></p>
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
  
   <?= $model->content?>
   
 
 

   
</div>
<script>
$(document).ready(function(){
	$('.fancybox').fancybox({
		closeClick : true,
	});
});
</script>
