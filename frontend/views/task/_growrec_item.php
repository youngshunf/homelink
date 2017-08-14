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
							<p>事项:<?= $model->items?></p>
							<p>获得学分: <span class="green"><?= $model->score?></span> </p>
							<p>班级:<?= $model->classname?></p>
							<p class="green">日期:<?= $model->item_time?></p>
						</div>
					
				</li>
			
</ul>

  
