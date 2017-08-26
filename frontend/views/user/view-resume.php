<?php
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\ResumePhoto;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name.'的简历';
$user=yii::$app->user->identity;
?>
<style>
.img-container{
	margin:5px
}
.mui-table-view:before{
	background:none;
}
</style>
  <h5><?= $this->title?></h5>
  <ul class="mui-table-view">
				
				<li class="mui-table-view-cell">
					<p><span class="bold">姓名</span>
					<span class="pull-right"><?= $model->name?></span>
					</p>
				</li>
			  <li class="mui-table-view-cell">
					<p><span class="bold">电话</span>
					<span class="pull-right"><?= $model->mobile?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">邮箱</span>
					<span class="pull-right"><?= $model->email?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">性别</span> <span class="pull-right"><?= CommonUtil::getDescByValue('user', 'sex', $model->sex)?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">毕业时间</span> <span class="pull-right"><?= CommonUtil::fomatDate($model->graduation_time)?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">毕业院校</span> <span class="pull-right"><?= $model->school?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">最高学历</span> <span class="pull-right"><?= $model->top_edu?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">自我介绍</span> </p>
					<p ><?= $model->desc?></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">简历照片</span> </p>
					 <div>
                    <?php $photos=ResumePhoto::findAll(['resumeid'=>$model->id]);
                   
                    foreach ($photos as $v){
                    ?>
                    <a href="<?= yii::getAlias('@photo').'/'.$v->path.$v->photo?>">
                    <div class="img-container">
                     <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$v->path.'thumb/'.$v->photo?>" class="img">
                    </div>
                    </a>
                    <?php }?>
                    </div>
				</li>

</ul>

<?php if($user->user_guid==$model->user_guid){?>
    <div class="bottom-btn">
     <a class="btn btn-primary btn-block btn-lg" href="<?= Url::to(['update-resume','id'=>$model->id])?>">修改简历</a> 
    </div>
<?php }?>