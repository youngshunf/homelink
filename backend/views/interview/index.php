<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInterviewDistrict */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'HM大区';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="interview-district-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
       <button class="btn btn-success"  id="import-data">导入大区数据</button>
     
    </p>
      <form action="<?= Url::to(['export-data'])?>" method="post" id="export-form" >
     <input type="hidden" name="_csrf" value="<?= yii::$app->request->getCsrfToken()?>">
     <div class="input-group">
         <span class="input-group-addon">选择月份</span>
         <input type="date" class="form-control" name="yearMonth" id="startTime">
         <span class="input-group-addon btn btn-success" id="export">导出面试结果</span>
      </div>
    </form>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'id',
            ],
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'district_code',
            'district_name',
            'assistant_number',
//             'assistant_name',
             'supervisor_number',
            // 'supervisor_name',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn',
               'header'=>'操作',
                'template'=>'{view}{update}{delete}{export}',
                'buttons'=>[
                    'view'=>function ($url,$model,$key){
                        return  Html::a('查看 | ', $url, ['title' => '查看','class'=>'interview-deny','data-id'=>$model->id,'data-status'=>'1'] );
                    },
                    'update'=>function ($url,$model,$key){
                        return  Html::a('修改 | ', $url, ['title' => '修改','class'=>'interview-deny','data-id'=>$model->id,'data-status'=>'2'] );
                    },
                    'delete'=>function ($url,$model,$key){
                        return  Html::a('删除 | ', $url, ['title' => '删除','data-confirm'=>'删除后该大区将不能报名，您确定要删除吗?'] );
                    },
                    'export'=>function ($url,$model,$key){
                    return  Html::a('导出报名数据', $url, ['title' => '导出'] );
                    },
                    ]
            ],
        ],
    ]); ?>

</div>

<!-- 导入查询关系 -->
<div class="modal fade" id="relationModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               导入查询关系
            </h4>
         </div>
         <div class="modal-body">
            	
              <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-data')?>" onsubmit="return check1()">						
        			<input type="file" value="文件" name="relation" id="relation" >	
        			<br>
        			<p class="red">*导入新的数据会将会覆盖以前的大区数据，请确保大区编号一致</p>
        			<input type="submit" value="导入大区数据"  class="btn btn-primary" >
        			
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
	$('#relationModal').modal('show');
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

 $('#batch').click(function(){
     var keys= $("#grid").yiiGridView("getSelectedRows");
     console.log(keys);
 })
 $('#export').click(function(){
	 showWaiting('正在导出,请稍候...');
	 $('#export-form').submit();
 });

 </script>
