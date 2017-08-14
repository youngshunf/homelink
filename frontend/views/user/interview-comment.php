<?php
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\controllers\InterviewRegisterController;
use common\models\InterviewResult;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->user->name.'-'.$result->job->name;
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
					<p><span class="bold">面试者:</span>
					<span ><?= $model->user->name?></span>
					</p>
					<p> 面试环节: <?= $model->stage?> 面</p>
					<?php if(!empty($result->job)){?>
					<p> 面试岗位: <?= $result->job->name?></p>
					<?php }?>
					<p> 面试结果: <?= CommonUtil::getDescByValue('interview','status',$model->status)?></p>
					<p><span >面试时间:<?= CommonUtil::fomatTime($model->time)?></span></p>
					<p><span >面试地址:<?= $model->address?></span></p>
					<?php if($model->status!=0){?>
					<p><span >面试评价:<?= $model->comment?></span></p>
					<?php }?>
					<?php if(!empty($result->recuser)){?>
					<p><span class="bold">推荐人:</span>
					<span ><?= $result->recuser->name?></span>
					</p>
					<?php }?>
					<p><span class="bold">毕业院校 : </span> <span ><?= $result->school?></span></p>
					<p><span class="bold">申请时间 : </span> <span ><?= CommonUtil::fomatTime($result->created_at)?></span></p>
					<p><span class="bold">当前状态 : </span> <span class="mui-badge mui-badge-success"><?= CommonUtil::getDescByValue('interview_result','status',$result->status)?></span></p>
				
				   <p class="center"><a class="btn btn-primary" href="<?= Url::to(['view-user-resume','user_guid'=>$model->user_guid])?>">查看简历</a></p>
				</li>

</ul>
<div>
<?php if($model->status==0){?>

<?php $form = ActiveForm::begin(['id'=>'comment-form','options' => []]); ?>

    <?= $form->field($model, 'status')->dropDownList(['1'=>'通过','2'=>'未通过']) ?>
    
   
    <?= $form->field($model, 'comment')->textarea(['rows'=>'4']) ?>
</div>


    <div class="form-group center bottom-btn">
        <?= Html::submitButton( '提交面试结果' , ['class' => 'btn btn-success btn-block btn-lg ' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php }?>
  

