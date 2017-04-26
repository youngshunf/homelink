<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use common\models\TaskResult;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = $model->name;
$user=yii::$app->user->identity;
?>
<style>

.content {
  padding: 8px;
  background: rgba(255, 255, 255, 1);
}
.wrap > .container {
  padding: 0;
}
h5{
	color:green;
	font-size:18px;
}
img{
	display: block;
	max-width:100%;
	height:auto;
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
    <h5>任务要求</h5>
   <?= $model->requirement?>
   
    <?php if($user->role_id==1){
    $hadTask=TaskResult::findAll(['task_id'=>$model->id,'user_guid'=>$user->user_guid]);
    if(!empty($hadTask)){
        ?>
    <h5>领取历史</h5>
    <ul class="mui-table-view">
    <?php foreach ($hadTask as $v){?>
    <li class="mui-table-view-cell">
    <p>【领取时间】 <?= CommonUtil::fomatTime($v->created_at)?></p>
    <p>【开始时间】 <?= CommonUtil::fomatDate($v->start_time)?></p>
    <p>【结束时间】 <?= CommonUtil::fomatDate($v->end_time)?></p>
    <p>   <a class="btn btn-danger btn-block"  href="<?= Url::to(['task-done','id'=>$v->id])?>" >您已完成此任务</a></p>
    </li>
    <?php }?>
    </ul>
    <?php } }?>
    
    <?php if($user->role_id==3 || $user->role_id==4){
    $dataProvider=new ActiveDataProvider([
        'query'=>TaskResult::find()->andWhere(['task_id'=>$model->id,'business_district'=>$user->business_district])->orderBy('created_at desc')
    ]);
        ?>
        <h5>领取任务MVP列表</h5>
    <?= ListView::widget([
    'dataProvider'=>$dataProvider,
    'itemView'=>'_result_item',
    'layout'=>"{items}\n{pager}"
    ]
    );?>
    
    <?php }?>
   
</div>
<?php if(!yii::$app->user->isGuest&&$user->role_id==1){
    ?>
<div class="bottom-btn">
    <a class="btn btn-success btn-block"  href="javascript:;" id="getTask">领取任务</a>
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
        			<input type="submit" value="确定领取"  class="btn btn-primary" >
        		  <p id="errorImport1"></p>		
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
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

 </script>