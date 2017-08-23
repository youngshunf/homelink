<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use wenyuan\ueditor\Ueditor;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
$this->title=$model->task_name.'-评分';
?>
<style>
.panel-white{
	background:#fff;
}
</style>
<div class="panel-white">

    <ul class="mui-table-view" style="margin: 8px">
				<li class="mui-table-view-cell mui-media">
					
						<img class="mui-media-object mui-pull-left" src="<?=yii::getAlias('@photo').'/'.$model->task->path.'thumb/'.$model->task->photo?>">
					
						<div class="mui-media-body">
						    <?= $model->task_name?>
							<p>【分值】<?= $model->task->score?></p>
							<p>【完成标准】<?= $model->task->standard?></p>
						</div>
					
				</li>
				<li class="mui-table-view-cell mui-media">
				<div class="mui-media-body">
				 <p>【被评价】</p>
				 <p>  姓名:<?= $model->user->real_name?></p>
				 <p>  工号:<?= $model->work_number?></p>
				 </div>
				</li>
			
	</ul>
	<div class="content">
    
    <?php $form = ActiveForm::begin(['options' => ['onsubmit'=>'return check()']]); ?>

    <?= $form->field($model, 'comment_result')->radioList(['0'=>'未完成','1'=>'已完成'])->label('任务完成情况') ?>
    
      <?= $form->field($model, 'score')->textInput(['type'=>'number']) ?>
    <?= $form->field($model, 'comment')->textarea(['rows'=>5]) ?>
    
    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success btn-block btn-lg' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
	</div>
</div>

<script>
function check(){
var required=0;
$('.required').find('input').each(function(){
    if(!$(this).val()){
        required++;
    }
});

 if(required!=0){
  modalMsg('请填写完再提交');
  return false;
 }

 var maxScore=parseInt(<?= $model->task->score?>);
 var score=parseInt($('#taskresult-score').val());
 if(score>maxScore){
	 modalMsg('得分不能超过最大分值');
	  return false;
 }

 showWaiting('正在提交,请稍候...');
 return true;

}

</script>