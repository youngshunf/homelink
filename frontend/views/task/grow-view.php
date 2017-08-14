<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use common\models\TaskResult;
use yii\widgets\ListView;
use common\models\User;
use common\models\GrowthRec;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */


$user=User::findOne(['work_number'=>$work_number]);
$this->title ='MVP成长记录-'. $user->real_name;
$score=TaskResult::find()->andWhere(['work_number'=>$work_number])->sum('score');
?>
<style>

.content {
	margin:0;
  padding: 8px;
  background: #fff;
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
<ul class="mui-table-view" >
				<li class="mui-table-view-cell mui-media">
						<img class="mui-media-object mui-pull-left" src="<?=$user->img_path?>">
						<div class="mui-media-body">
							<p><?php if(!empty($user->real_name)) echo '姓名:'.$user->real_name;?></p>
							<p> 工号 : <?= $user->work_number?></p>
							<p> 电话 : <?= $user->mobile?></p>
							<p> 业务区域 : <?= $user->business_district?></p>
							<p> 负责店面 : <?= $user->shop?></p>
							<p> 总学分 :<span class="green"> <?= $score?> </span> </p>
						</div>
				</li>
</ul>
	
	<?php
     $growData=new ActiveDataProvider([
         'query'=>GrowthRec::find()->andWhere(['work_number'=>$work_number])->orderBy('item_time desc'),
     ]);
	echo  ListView::widget([
	    'dataProvider'=>$growData,
	    'itemView'=>'_growrec_item',
	    'layout'=>"{items}\n{pager}"
    ]);?>
	
	<br>
	<?php 
/* 	$taskResult=new ActiveDataProvider([
	    'query'=>TaskResult::find()->andWhere(['work_number'=>$work_number,'is_comment'=>1])->orderBy('created_at desc')
	]);
	echo ListView::widget([
	'dataProvider'=>$taskResult,
	'itemView'=>'_mvp_grow_item',
	'layout'=>"{items}\n{pager}"
	    ]) */
	?>
	
	
</div>

  
  