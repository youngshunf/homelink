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

								<ul class="mui-table-view ">
									<li class="mui-table-view-cell mui-media">
									<a   href="<?= Url::to(['view','id'=>$model->id])?>">
									<p class="bold b-padding"> <?= $model->name?></p>
									<img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
										<p class="p-padding"> <?=$model->standard?></p>
										
										<p class="green">任务时间: <?= CommonUtil::fomatTime($model->start_time )?> - <?= CommonUtil::fomatTime($model->end_time )?> </p>
									</a>
									</li>	
									
								<?php if($user->role_id==3 || $user->role_id==4){ ?>
								 <li class="mui-table-view-cell">
										<p>总共 <span class="red"><?= $resultCount?></span> 个MVP领取此任务,您已对 <span class="green"><?= $commentCount?></span>  个MVP进行了评分</p>
									</li>
								<?php }?>	
																												
								</ul>

  
