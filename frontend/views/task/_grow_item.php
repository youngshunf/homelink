<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\TaskResult;

?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
						<img class="mui-media-object mui-pull-left" src="<?=$model->img_path?>">
						<div class="mui-media-body">
							<?php if(!empty($model->real_name)) echo '姓名:'.$model->real_name;?>
							<p>工号:<?= $model->work_number?></p>
							<p>电话:<?= $model->mobile?></p>
							<p class="center">
							<a class="btn btn-primary"  href="<?= Url::to(['grow-view','work_number'=>$model->work_number])?>">查看MVP成长记录</a>
							</p>
						</div>
					
				</li>
			
</ul>

  
