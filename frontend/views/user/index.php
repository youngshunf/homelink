<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\CommonUtil;


/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '个人中心-'.$model->real_name;
$this->params['breadcrumbs'][] = $this->title;
$user=yii::$app->user->identity;
?>
<style>
.mui-table-view.user-info:before{
	background:none
}

.mui-table-view.user-info:after{
	background:none
}

.mui-table-view .mui-media-object{
	line-height: 70px;
    max-width: 70px;
    height: 70px;
	margin-bottom:10px
}
.icon-img{
	width:25px
}
</style>
 <div class="panel-white">
<ul class="mui-table-view user-info" style="margin-top: 0;">
	<li class="mui-table-view-cell mui-media " id="user-info">
			<a href="<?= Url::to(['profile','id'=>$model->id])?>" class="text-center mui-navigate-right">
				<?php if(!empty($model->photo)){?>
						<img class=" mui-media-object img-circle img-responsive head-img"  src="<?= yii::$app->params['photoUrl'].$model->path.'thumb/'.$model->photo?>">
					<?php } elseif (!empty($model->img_path)){?>
					<img class=" mui-media-object img-circle  img-responsive head-img"  src="<?= $model->img_path?>" >
					<?php }else{?>
						<img class=" mui-media-object img-circle img-responsive head-img"  src="<?= yii::$app->params['uploadUrl']?>/avatar/unknown.jpg">
						
						<?php }?>
				<div class="mui-media-body">
					<p id="name"><?= $model->real_name?></p>
					<p class='mui-ellipsis' id="mobile" ><?= $model->mobile?></p>
					<p  ><span class='mui-badge mui-badge-success' ><?= CommonUtil::getDescByValue('user', 'role_id', $model->role_id)?></span></p>
				</div>
			</a>
	</li>
		
	</ul>
<ul class="mui-table-view"  style="margin-top: 5px;">
        
		<li class="mui-table-view-cell"  >
			<a class="" href="<?= Url::to(['my-activity'])?>">
				我的活动  <span class="mui-pull-right "><img src="img/activity.png" class="icon-img"> </span>
			</a>
		</li>
		<li class="mui-table-view-cell"  >
			<a class="" href="<?= Url::to(['my-report'])?>">
				测评统计  <span class="mui-pull-right "><img src="img/test.png" class="icon-img"> </span>
			</a>
		</li>
		<li class="mui-table-view-cell"  >
			<a class="" href="<?= Url::to(['task/mvp-grow'])?>">
				成长路径  <span class="mui-pull-right "><img src="img/grow.png" class="icon-img"> </span>
			</a>
		</li>
		<li class="mui-table-view-cell"  >
			<a class="mui-navigate-right" href="<?= Url::to(['site/logout'])?>">
				退出登录 
			</a>
		</li>
</ul>


    
   
</div>

