<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;

?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
					
						<img class="mui-media-object mui-pull-left" src="<?=$model->user->img_path?>">
					
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
						<a class="btn btn-success "  href="<?= Url::to(['grow-view','work_number'=>$model->work_number])?>">查看mvp成长记录</a>
						<a class="btn btn-success " href="<?= Url::to(['comment-task','id'=>$model->id])?>">去评分</a>
						</p>
						<?php }else{?>
						<p >
						   得分 : <span class="green"><?= $model->score?></span></p>
						<p >评价：<?= $model->comment?></p>
						<p class="center">
						<a class="btn btn-success "  href="<?= Url::to(['grow-view','work_number'=>$model->work_number])?>">查看mvp成长记录</a>
						<a class="btn btn-success " href="<?= Url::to(['comment-task','id'=>$model->id])?>">修改</a>
						</p>
						<?php }?>
						</div>
					
				</li>
			
	</ul>

  
