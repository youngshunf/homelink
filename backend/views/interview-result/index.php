<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInterviewResult */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '面试结果';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增', ['create'], ['class' => 'btn btn-success']) ?>
     <a href="javascript:;" class="btn btn-success" id="import-data">导入数据</a>
     <?= Html::a('导出数据', ['export-data'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            'id_code',
            'level',
            'rec_work_number',
             'rec_name',
             'interview_time',
            'interview_result',
             //'train_result',
            // 'status',
            // 'remark',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn','header'=>'操作'],
        ],
    ]); ?>

</div>

<!-- 导入查询关系 -->
<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               导入面试结果
            </h4>
         </div>
         <div class="modal-body">
            	
              <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-data')?>" onsubmit="return check1()">						
        			<input type="hidden"  name="_csrf" value="<?= yii::$app->request->csrfToken?>">
        			<input type="file" value="文件" name="relation" id="relation" >	
        			<br>
        			<input type="submit" value="导入面试结果"  class="btn btn-primary" >
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
$('#import-data').click(function(){
	$('#dataModal').modal('show');
 });
function check1(){
	var importfrom = $("#relation").val();
	if(importfrom==""){
		$("#errorImport1").html("<font color='red'>请选择导入的文件</font>");
		return false;
	}else{
		showWaiting('正在导入,请稍候...');
		return true;
	}
}
 </script>