<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;

?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
    				<div class="mui-media-body">
    					<p>面试时间: <?= CommonUtil::fomatTime($model->time)?></p>
    					<p>面试地址: <?= $model->address?></p>
    					<p>面试环节: <?= $model->stage?>面</p>
    					<p>面试结果: <?= CommonUtil::getDescByValue('interview','status',$model->status)?></p>
    					<?php if($model->status!=0){?>
    					<p>面试评价:<?= $model->comment?></p>
    					<?php }?>
    				</div>
				</li>
			
	</ul>

  
