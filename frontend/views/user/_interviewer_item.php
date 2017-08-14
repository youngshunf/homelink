<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;

?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
				<a href="<?= Url::to(['interview-comment','id'=>$model->id])?>" class="mui-navigate-right">
						<div class="mui-media-body">
							<h4>面试者 : <?= $model->user->name?></h4>
							<p> 面试环节: <?= $model->stage?> 面</p>
							<?php if(!empty($model->job)){?>
							<p> 面试岗位: <?= $model->job->name?></p>
							<?php }?>
							<p> 面试结果: <?= CommonUtil::getDescByValue('interview','status',$model->status)?></p>
							<p><span >面试时间:<?= CommonUtil::fomatTime($model->time)?></span></p>
							<p><span >面试地址:<?= $model->address?></span></p>
						</div>
				</a>
				</li>
			
	</ul>

  
