<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInterviewDistrict */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '面试地址-'.date('Y年m月');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-district-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $data,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'district_code',
            'district_name',
            'year_month',
            ['attribute'=>'time',
            'value'=>function ($model){
            return CommonUtil::fomatHours($model->time);
            }
            ],
             'address',
            // 'created_at',
//             ['class' => 'yii\grid\ActionColumn',
//                'header'=>'操作',
//                 'template'=>'{appeal-deal}',
//                 'buttons'=>[
//                     'appeal-deal'=>function ($url,$model,$key){
//                     if($model->is_appeal==1){
//                         return  Html::a('申诉处理', '#', ['title' => '处理','class'=>'appeal-deal','data-id'=>$model->id] );
//                      }
//                     },
                   
//                     ]
                
//     ],
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
              申诉处理
            </h4>
         </div>
         <div class="modal-body">
            	
              <form method="post" action="<?php echo Url::to('appeal-deal')?>" onsubmit="return check()">						
                <input type="hidden"  name="_csrf" value="<?= yii::$app->request->referrer?>">
                 <input type="hidden"  name="id" id="rid" >
                 <div class="form-group">
                   <label>面试结果</label>
        			<select name="interview_result" class="form-control">
        			<option value='1'>通过</option>
        			<option value='2'>不通过</option>
        			</select>
        		</div>
                <div class="form-group">
        			<label>处理意见</label>
        			<textarea id="remark" class="form-control" rows="5" name="remark" placeholder="请输入处理意见(非必填)"></textarea>
        		</div>
        			
        			
        			<br>
        			<p class="center">
        			<input type="submit" value="确定提交"  class="btn btn-primary" >
        			</p>
        			
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
</div>

<script>
$('.appeal-deal').click(function(e){
	e.stopPropagation();
	$('#taskModal').modal('show');
	var id=$(this).data('id');
	console.log(id);
	$('#rid').val(id);
	
 });
function check(){
	
		showWaiting('正在提交,请稍候...');
		return true;
}
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
 </script>
