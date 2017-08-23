<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = $model->title;

?>
<style>


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
  <?php if($model->type==1){?>
  <p><label class="label-control">竞聘店面:</label><?= $registerModel->sign_shop?></p>
  <?php }?>
  
  <?php if($model->type==3){?>
  <p><label class="label-control">大区:</label><?= $registerModel->district_name?></p>
  <?php }?>
 
 
</div>
<div class="content">  
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
 </div>
 
 <div class="content">
   <h5>活动介绍</h5>
   <?= $model->content?>
 </div>  
 

