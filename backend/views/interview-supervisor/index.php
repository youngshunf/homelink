<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInterviewDistrict */
/* @var $dataProvider yii\data\ActiveDataProvider */
$user=yii::$app->user->identity;
$this->title = 'HM面试'.$user->district_name.date('Ym');

?>
<div class="panel-white">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <a class="btn btn-info" href="<?= Url::to(['export-register'])?>">导出报名结果</a>
        <a class="btn btn-info" href="<?= Url::to(['export-result'])?>">导出面试结果</a>
       <button class="btn btn-success"  id="batch-pass-signup">符合面试资格</button>
       <button class="btn btn-danger"  id="batch-deny-signup">不符合面试资格</button>
       <a class="btn btn-info" href="<?= Url::to(['send-signup-message'])?>">发送报名结果通知</a>
       <a class="btn btn-info" href="<?= Url::to(['send-interview-message'])?>">发送面试结果通知</a>
       
<!--        <button class="btn btn-success"  id="export-data">导出数据</button> -->
<!--        <button class="btn btn-success"  id="export-data">导出数据</button> -->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'id',
            ],
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            
            'district_code',
            'district_name',
             'name',
             'mobile',
             'work_number',
            'year_month',
            'remark',
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
            // 'created_at',

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

</div>

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
            	
              <form method="post" action="<?php echo Url::to('interview-supervisor/deny')?>" onsubmit="return check()">						
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
$('.interview-deny').click(function(e){
	e.stopPropagation();
	$('#taskModal').modal('show');
	var id=$(this).data('id');
	var status=$(this).data('status');
	console.log(id);
	$('#rid').val(id);
	$('#status').val(status);
	
 });
function check(){
	if(!$('#remark').val()){
       modalMsg('请输入面试评价');
       return false;
	}
		showWaiting('正在提交,请稍候...');
		return true;
}


$('#batch-deny-signup').click(function(){
    var keys= $("#grid").yiiGridView("getSelectedRows");
    console.log(keys);
    if(keys.length<=0){
      modalMsg('请至少选择一个');
      return false;
    }
    showWaiting('正在执行,请稍候...');
    $.ajax({
    	type:"post",
    	url:"<?= Url::to(['signup-deny'])?>",
    	data:{
        	keys:keys
    	},
    	success:function(rs){
        	console.log(rs);
			closeWaiting();
			location.reload();
    	},
    	error:function(e){
    		closeWaiting();
    		modalMsg('操作失败:'+e.status);
    	}

    })
})

$('#batch-pass-signup').click(function(){
    var keys= $("#grid").yiiGridView("getSelectedRows");
    console.log(keys);
    if(keys.length<=0){
        modalMsg('请至少选择一个');
        return false;
      }
      showWaiting('正在执行,请稍候...');
      $.ajax({
      	type:"post",
      	url:"<?= Url::to(['signup-pass'])?>",
      	data:{
          	keys:keys
      	},
      	success:function(rs){
          	console.log(rs);
  			closeWaiting();
  			location.reload();
      	},
      	error:function(e){
      		closeWaiting();
      		modalMsg('操作失败:'+e.status);
      	}

      })
})
 </script>
