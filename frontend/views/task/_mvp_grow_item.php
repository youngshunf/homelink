<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;

?>

   <ul class="mui-table-view" style="margin: 8px">
				<li class="mui-table-view-cell mui-media">
						<img class="mui-media-object mui-pull-left" src="<?=yii::getAlias('@photo').'/'.$model->task->path.'thumb/'.$model->task->photo?>">
						<div class="mui-media-body">
						    任务名称 : <?= $model->task_name?>
							<p>【分值】<?= $model->task->score?></p>
							<p>【完成标准】<?= $model->task->standard?></p>
						</div>
					
				</li>
				<li class="mui-table-view-cell mui-media">
				 <p>【得分】<?= $model->score?></p>
				 <p> 【评价】<?= $model->comment?></p>
				 <p>  【评分人】<?= $model->commentUser->real_name?></p>
				  <p>  【评分时间】<?= CommonUtil::fomatTime($model->updated_at)?></p>
				</li>
			
	</ul>

  
