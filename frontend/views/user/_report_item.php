<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;
use common\models\ActivityStep;

$user=yii::$app->user->identity;
?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
				<a href="<?= Url::to(yii::$app->params['fileUrl'].$model->path.$model->photo)?>" class="mui-navigate-right">
						<div class="mui-media-body row">
							<h4><?= $model->name?></h4>
							<?php if($model->user){?>
							<p>姓名:<?= $model->user->real_name?></p>
							<p>身份:<span class="mui-badge mui-badge-success"><?= CommonUtil::getDescByValue('user', 'role_id', $model->user->role_id)?></span>
							<?php if(($user->role_id==3 || $user->role_id==4) && $model->work_number!=$user->work_number ){?>
							<span class="mui-badge mui-badge-warning">我的下级</span>
							<?php }?>
							</p>
							<?php }?>
							<p>工号:<?= $model->work_number?></p>
							<p><span class="green">报告时间:<?= CommonUtil::fomatTime($model->report_time)?></span></p>
						</div>
				</a>
				</li>
			
	</ul>

  
