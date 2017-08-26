<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInterviewDistrict */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '面试地点';
$user=yii::$app->user->identity;
?>
<div class="panel-white">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <a class="btn btn-info" href="<?= Url::to(['create'])?>">新增面试地点</a>
       
       
<!--        <button class="btn btn-success"  id="export-data">导出数据</button> -->
<!--        <button class="btn btn-success"  id="export-data">导出数据</button> -->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
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

            ['class' => 'yii\grid\ActionColumn',
               'header' => '操作',
			 'template'=>'{update}',
             'buttons'=>[
				'update'=>function ($url,$model,$key){
				        return  Html::a('修改 ', $url, ['title' => '修改'] );
				}
				
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
               面试不通过
            </h4>
         </div>
         <div class="modal-body">
            	
              <form method="post" action="<?php echo Url::to('interview-supervisor/deny')?>" onsubmit="return check()">						
                <input type="hidden"  name="_csrf" value="<?= yii::$app->request->referrer?>">
                 <input type="hidden"  name="id" id="rid" >
                <div class="form-group">
        			<label>不通过原因</label>
        			<textarea id="remark" class="form-control" rows="5" name="remark" placeholder="请输入不通过原因(必填)"></textarea>
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
	console.log(id);
	$('#rid').val(id);
	
 });
function check(){
	if(!$('#remark').val()){
       modalMsg('请输入不通过原因');
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
