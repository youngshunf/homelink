<?php
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ListView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->user->name.'-'.$model->job->name;
$user=yii::$app->user->identity;
?>
<style>
img{
	max-width:70px
}
.mui-table-view:before,.mui-table-view:after{
	background:none;
}
.mui-table-view-cell p {
    margin-bottom: 10px;
}
</style>
  <h5><?= $this->title?></h5>
  <ul class="mui-table-view">
				
				<li class="mui-table-view-cell">
					<p><span class="bold">面试岗位:</span>
					<span ><?= $model->job->name?></span>
					</p>
					<p><span class="bold">岗位分类:</span>
					<span ><?= $model->jobcate->name?></span>
					</p>
					<?php if(!empty($model->recuser)){?>
					<p><span class="bold">推荐人:</span>
					<span ><?= $model->recuser->name?></span>
					</p>
					<?php }?>
					<p><span class="bold">毕业院校 : </span> <span ><?= $model->school?></span></p>
					<p><span class="bold">申请时间 : </span> <span ><?= CommonUtil::fomatTime($model->created_at)?></span></p>
					<p><span class="bold">当前状态 : </span> <span class="mui-badge mui-badge-success"><?= CommonUtil::getDescByValue('interview_result','status',$model->status)?></span></p>
				 <?php if(($user->role_id==3 || $user->role_id==4) && ($model->status==0 || $model->status==2)){?>
				  <p class="center">
				  <a class="btn btn-primary" href="<?= Url::to(['assign','id'=>$model->id])?>">分配面试官</a>
				  </p>
				  <?php }?>
				</li>

</ul>
<h5 >面试记录</h5>
<?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_result_item',            
           'layout'=>"{items}\n{pager}"
 ])?>
 
 


  

