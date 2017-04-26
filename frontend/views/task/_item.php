<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;

$user=yii::$app->user->identity;
if($user->role_id==3 || $user->role_id==4){
$resultCount=TaskResult::find()->andWhere(['task_id'=>$model->id,'business_district'=>$user->business_district])->count();
$commentCount=TaskResult::find()->andWhere(['task_id'=>$model->id,'business_district'=>$user->business_district,'comment_user'=>$user->user_guid,'is_comment'=>1])->count();
}
?>

<div class="mui-card" style="margin-bottom: 8px">	
								<ul class="mui-table-view ">
									<li class="mui-table-view-cell ">																																								
										<p>【任务名称】 <?= $model->name?></p>										
									</li>
									<li class="mui-table-view-cell mui-media">
									<img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
										<p>【任务分值】<span class="red"><?=$model->score?></span></p>
										<p>【完成标准】 <?=$model->standard?></p>
									</li>	
									
								<?php if($user->role_id==3 || $user->role_id==4){ ?>
								<li class="mui-table-view-cell">
										<p>总共 <span class="red"><?= $resultCount?></span> 个MVP领取此任务,您已对 <span class="green"><?= $commentCount?></span>  个MVP进行了评分</p>
									</li>
									<li class="mui-table-view-cell">
										<div class="center">
										<a class="mui-btn mui-btn-primary"  href="<?= Url::to(['view','id'=>$model->id])?>">去评分</a>
										</div>
									</li>
								<?php }elseif ($user->role_id==1){?>
									<li class="mui-table-view-cell">
										<div class="center">
										<a class="mui-btn mui-btn-primary"  href="<?= Url::to(['view','id'=>$model->id])?>">领取任务</a>
										</div>
									</li>
									<?php }?>																					
								</ul>
	</div>	

  
