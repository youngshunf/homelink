<?php

use yii\web\View;
use common\models\CommonUtil;
use yii\helpers\Url;

?>
<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
				<a href="<?= Url::to(['profile','id'=>$model->id]) ?>">
					<?php if(!empty($model->photo)){?>
					  
						<img class="mui-media-object mui-pull-left" src="<?=yii::$app->params['photoUrl'].$model->path.'thumb/'.$model->photo?>">
					<?php }elseif(!empty($model->img_path)){?>
						<img class="mui-media-object mui-pull-left" src="<?=$model->img_path?>">
					<?php }else{?>
						<img class="mui-media-object mui-pull-left" src="<?=yii::$app->params['uploadUrl'].'/avatar/unknown.jpg'?>">
					<?php }?>
						<div class="mui-media-body">
							<?php if(!empty($model->real_name)) echo '姓名:'.$model->real_name;?>
							<p>工号:<?= $model->work_number?></p>
							<p>电话:<?= $model->mobile?></p>
							<p>角色: <span class="mui-badge mui-badge-success"><?= CommonUtil::getDescByValue('user', 'role_id', $model->role_id)?></span></p>
						
						</div>
					</a>
				</li>
			
	</ul>
  
