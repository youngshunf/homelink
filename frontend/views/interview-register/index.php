<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\InterviewPhoto;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInterviewRegister */
/* @var $dataProvider yii\data\ActiveDataProvider */
$user=yii::$app->user->identity;
$this->title = '报名结果-'.$user->district_name;
$year_month=date('Ym');
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'work_number',
            'name',
            'mobile',
            ['attribute'=>'signup_result',
            'value'=>function ($model){
            return CommonUtil::getDescByValue('interview_register', 'signup_result', $model->signup_result);
            }
            ],
            ['attribute'=>'interview_result',
                'value'=>function ($model){
                return CommonUtil::getDescByValue('interview_register', 'interview_result', $model->interview_result);
                }
             ],
            // 'signup_result',
            // 'interview_result',
            // 'remark',
            // 'is_appeal',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn',
               'header' => '操作',
			 'template'=>'{pass}{deny}{appeal}',
             'buttons'=>[
				'pass'=>function ($url,$model,$key){
				    if($model->signup_result==1 && $model->interview_result==0){
				        return  Html::a('通过', '#', ['title' => '通过','class'=>'interview-deny','data-id'=>$model->id,'data-status'=>'1'] );
				    }
				},	
				'deny'=>function ($url,$model,$key){
				if($model->signup_result==1 && $model->interview_result==0){
				    return  Html::a('不通过', '#', ['title' => '不通过','class'=>'interview-deny','data-id'=>$model->id,'data-status'=>'2'] );
				 }
				},
				'appeal'=>function ($url,$model,$key){
    				if($model->signup_result==1 && $model->interview_result!=0 && $model->is_appeal==0){
    				    return  Html::a('申请修改', $url, ['title' => '申请修改','data-confirm'=>'申请修改后不能撤销,将由管理员最终决定面试结果,您确定要申请修改?'] );
    				}
				},
			]
    ],
        ],
    ]); ?>
	
	  <div class="form-group center">
    <label class="control-label"> 总监签字照片</label>
    <div class="row">
    <?php 
    $photo=InterviewPhoto::findAll(['district_code'=>$user->district_code,'year_month'=>$year_month,'type'=>'1']);
    if(!empty($photo)){
    foreach ($photo as $v){?>
    <div class="col-xs-6" style="margin:10px">
            <img alt="总监签字照片" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
    </div>
    <?php }}?>
    </div>
    <div class="img-container center" data-type="1">
            <div class="uploadify-button"> 
            </div>
    </div>
    <form action="<?= Url::to(['upload-photo'])?>" id="photo-form" method="post" enctype="multipart/form-data">
   <input type="file" name="photo"  class="hide"  >
   <input type="hidden" name="type" id="type"    >
   </form>
   </div>
   
     <div class="form-group center">
    <label class="control-label"> 面试现场照片</label>
    <div class="row">
    <?php 
    $photo1=InterviewPhoto::findAll(['district_code'=>$user->district_code,'year_month'=>$year_month,'type'=>'2']);
    if(!empty($photo1)){
    foreach ($photo1 as $v){?>
    <div class="col-xs-6" style="margin:10px">
            <img alt="总监签字照片" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
    </div>
    <?php }}?>
    </div>
    <div class="img-container center" data-type="2">
    <div class="uploadify-button"> 
            </div>
    </div>
    
   </div>
   
</div>
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
               面试评价
            </h4>
         </div>
         <div class="modal-body">
              <form method="post" action="<?php echo Url::to('deny')?>" onsubmit="return check()">						
                <input type="hidden"  name="_csrf" value="<?= yii::$app->request->referrer?>">
                 <input type="hidden"  name="id" id="rid" >
                 <input type="hidden"  name="status" id="status" >
                <div class="form-group">
        			<label>面试评价</label>
        			<textarea id="remark" class="form-control" rows="5" name="remark" placeholder="请输入面试评价(必填)"></textarea>
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
$('.interview-deny').click(function(){
	var id=$(this).data('id');
	var status=$(this).data('status');
	console.log(id);
	$('#rid').val(id);
	$('#status').val(status);
	$('#taskModal').modal('show');
 });
function check(){
	if(!$('#remark').val()){
       modalMsg('请输入不通过原因');
       return false;
	}
		showWaiting('正在提交,请稍候...');
		return true;
}

$('.img-container').click(function(){
	var type=$(this).data('type');
	$('#type').val(type);
	$('input[type=file]').click();
});

$("input[type=file]").change( function () {
	console.log('.photo click');
    var that = $(this);
    showWaiting('正在提交,请稍候...');
    $('#photo-form').submit();
});

 </script>
