<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;
use common\models\ActivityStep;

?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
				<a href="<?= Url::to(['task/view','id'=>$model->task->id])?>" class="mui-navigate-right">
						<div class="mui-media-body row">
						   <?php if($model->task){?>
							<h4><?= $model->task->name?></h4>
							<div class="col-xs-3">
							<img class="img-responsive" src="<?= yii::$app->params['photoUrl'].$model->task->path.'thumb/'.$model->task->photo?>">
							</div>
							
							<?php }?>
							<div class="col-xs-9">
							 <?php if($model->task){?>
							<p> <?= $model->task->standard?></p>
							<?php }?>
							<p><span class="green">计划开始时间:<?= CommonUtil::fomatTime($model->start_time)?></span></p>
							<p><span class="green">计划结束时间:<?= CommonUtil::fomatTime($model->end_time)?></span></p>
							</div>
						</div>
				</a>
				</li>
			
	</ul>

  
