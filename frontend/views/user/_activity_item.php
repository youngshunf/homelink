<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;
use common\models\ActivityStep;

$curentStep=ActivityStep::findOne(['activity_id'=>$model->activity_id,'step'=>$model->current_step]);
?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
				<a href="<?= Url::to(['activity-step','id'=>$model->register_id])?>" class="mui-navigate-right">
						<div class="mui-media-body row">
						   <?php if($model->activity){?>
							<h4><?= $model->activity->title?></h4>
							<div class="col-xs-3">
							<img class="img-responsive" src="<?= yii::$app->params['photoUrl'].$model->activity->path.'thumb/'.$model->activity->photo?>">
							</div>
							
							<?php }?>
							<div class="col-xs-9">
							<?php if(!empty($curentStep)){?>
							<p>当前环节: <?= $curentStep->title?></p>
							<p>当前状态: <?= CommonUtil::getDescByValue('step', 'status', $model->current_status)?></p>
							<?php }?>
							<p><span class="green">报名时间:<?= CommonUtil::fomatTime($model->created_at)?></span></p>
							</div>
						</div>
				</a>
				</li>
			
	</ul>

  
