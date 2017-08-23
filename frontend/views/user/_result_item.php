<?php

use yii\web\View;
use common\models\CommonUtil;
use yii\helpers\Url;

?>
<?php if(!empty($model->user)){?>
<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
					<?php if(!empty($model->user->photo)){?>
					  
						<img class="mui-media-object mui-pull-left" src="<?=yii::$app->params['photoUrl'].$model->user->path.'thumb/'.$model->user->photo?>">
					<?php }elseif(!empty($model->user->img_path)){?>
						<img class="mui-media-object mui-pull-left" src="<?=$model->user->img_path?>">
					<?php }else{?>
						<img class="mui-media-object mui-pull-left" src="<?=yii::$app->params['uploadUrl'].'/avatar/unknown.jpg'?>">
					<?php }?>
						<div class="mui-media-body">
							<?php if(!empty($model->user->real_name)) echo '姓名:'.$model->user->real_name;?>
							<?php if($model->is_comment==0){?>
							 <span class="mui-badge mui-badge-danger pull-right">未评分</span>
							<?php }else{?>
							 <span class="mui-badge mui-badge-success  pull-right">已评分</span>
							<?php }?>
							<p>工号:<?= $model->work_number?></p>
							<p>电话:<?= $model->user->mobile?></p>
							<p><span>领取时间:<?= CommonUtil::fomatTime($model->created_at)?></span></p>
						<?php if($model->is_comment==0){?>
							<p class="center">
						<a class="btn btn-success"  href="<?= Url::to(['task/grow-view','work_number'=>$model->work_number])?>">查看mvp成长记录</a>
						<a class="btn btn-success " href="<?= Url::to(['task/comment-task','id'=>$model->id])?>">去评分</a>
						</p>
						<?php }else{?>
						<p >
						   得分 : <span class="green"><?= $model->score?></span></p>
						<p >评价：<?= $model->comment?></p>
						<p class="center">
						<a class="btn btn-success"  href="<?= Url::to(['task/grow-view','work_number'=>$model->work_number])?>">查看mvp成长记录</a>
						<a class="btn btn-success " href="<?= Url::to(['task/comment-task','id'=>$model->id])?>">修改</a>
						</p>
						<?php }?>
						</div>
					
				</li>
			
	</ul>
<?php }?>
  
