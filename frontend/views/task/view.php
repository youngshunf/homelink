<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use common\models\TaskResult;
use yii\widgets\ListView;
use yii\web\View;
use common\models\TaskStep;
use common\models\AuthUser;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = $model->name;
$user=yii::$app->user->identity;
$this->registerJsFile('@web/js/vue.min.js', ['position'=> View::POS_HEAD]);
$this->registerJsFile('@web/js/iview.min.js', ['position'=> View::POS_HEAD]);
$this->registerCssFile('@web/css/iview.css', ['position'=> View::POS_HEAD]);
$steps=TaskStep::find()->andWhere(['task_id'=>$model->id])->orderBy('step asc')->all();
?>
<style>

.wrap > .container {
  padding: 0;
}
img{
	display: block;
	max-width:100%;
	height:auto;
}
#steps{
	margin:20px
}
</style>
  <div class="c_img">
            <img alt="<?= $model->name?>" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
            <div class="c_words">
            <?=$model['name']?>
            </div> 
    </div>
  <div class="content">
  <h5><?=$model['name']?></h5>
  <p>【任务分值】<span class="red"><?= $model->score?></span></p>
    <p>【完成标准】<?= $model->standard?></p>
  </div>
   <div class="content">
    <h5>任务要求</h5>
    <div>
   <?= $model->requirement?>
   </div>
  </div>
  
  <div class="content">
    <h5>任务进度</h5>
  <div id="steps" >
    <Steps :current="<?= $model->current_step-1?>" direction="vertical">
    <?php foreach ($steps as $v){?>
         <Step title="<?= CommonUtil::getDescByValue('step', 'status', $v->status)?>" content="<?= $v->content?>"></Step>
     <?php }?>
    </Steps>

</div>  
  </div>
  
  
    <?php if($user->role_id==1){
    $hadTask=TaskResult::findAll(['task_id'=>$model->id,'user_guid'=>$user->user_guid]);
    if(!empty($hadTask)){
        ?>
     <div class="content">     
    <h5>领取历史</h5>
    <ul class="mui-table-view">
    <?php foreach ($hadTask as $v){?>
    <li class="mui-table-view-cell">
    <p>【领取时间】 <?= CommonUtil::fomatTime($v->created_at)?></p>
    <p>【开始时间】 <?= CommonUtil::fomatDate($v->start_time)?></p>
    <p>【结束时间】 <?= CommonUtil::fomatDate($v->end_time)?></p>
    <?php if($v->status==0){?>
    <p>   <a class="btn btn-danger"  href="<?= Url::to(['task-done','id'=>$v->id])?>" >确认完成任务</a></p>
    <?php }?>
    </li>
    <?php }?>
    </ul>
    </div>
    <?php } }?>
    
    <?php if($user->role_id==3 || $user->role_id==4){
     $downUser=AuthUser::findAll(['up_work_number'=>$user->work_number]);
     $work_numbers=[];
     foreach ($downUser as $v){
         $work_numbers[]=$v->work_number;
     }
     $dataProvider=new ActiveDataProvider([
        'query'=>TaskResult::find()->andWhere(['task_id'=>$model->id,'work_number'=>$work_numbers])->orderBy('created_at desc')
    ]);
        ?>
        <div class="content"> 
        <h5>下级领取记录</h5>
    <?= ListView::widget([
    'dataProvider'=>$dataProvider,
    'itemView'=>'_result_item',
    'layout'=>"{items}\n{pager}"
    ]
    );?>
    </div>
    <?php }?>
   

<?php if(!yii::$app->user->isGuest&& ($user->role_id==1 || $user->role_id==5 || $user->role_id==6)){
    ?>
<div class="bottom-btn">
    <a class="btn btn-success btn-block btn-lg"  href="javascript:;" id="getTask">领取任务</a>
</div>

<?php }?>

<!--领取任务 -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               领取任务
            </h4>
         </div>
         <div class="modal-body">
            	
              <form method="post" action="<?php echo Url::to('get-task')?>" onsubmit="return check()">						
                <input type="hidden"  name="_csrf" value="<?= yii::$app->request->referrer?>">
                 <input type="hidden"  name="task_id" value="<?= $model->id ?>">
              <div class="form-group">
        			<label>计划开始时间</label>
        			<input type="date" class="form-control" name="start_time">
        			</div>
        			
        			<div class="form-group">
        			<label>计划结束时间</label>
        			<input type="date" class="form-control" name="end_time">
        			</div>
        			
        			<br>
        			<input type="submit" value="确定领取"  class="btn btn-success btn-block btn-lg" >
        		  <p id="errorImport1"></p>		
            </form>
         </div>
         <div class="modal-footer center">
            <button type="button" class="btn btn-success "  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->
<script>
$('#getTask').click(function(){
	$('#taskModal').modal('show');
 });
function check(){
  if(!$("input[name=start_time]").val()){
	    modalMsg('请选择开始时间');
	    return false;
  }
  if(!$("input[name=end_time]").val()){
	    modalMsg('请选择开始时间');
	    return false;
}
		showWaiting('正在提交,请稍候...');
		return true;
	}
	
new Vue({
    el: '#steps',
    data:function(){
    },
    created:function(){
    },
    computed:{
    },
    watch:{
    },
    methods:{
   	
      },
})
 </script>