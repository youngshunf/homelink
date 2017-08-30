<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">


    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            ['attribute'=>'start_time','value'=>
                CommonUtil::fomatTime($model->start_time)
            ],
            ['attribute'=>'end_time','value'=>
                CommonUtil::fomatTime($model->end_time)
            ],
            ['attribute'=>'report_time','value'=>
                CommonUtil::fomatTime($model->report_time)
            ],
            ['attribute'=>'created_at','value'=>
                CommonUtil::fomatTime($model->created_at)
            ],
            ['attribute'=>'updated_at','value'=>
                CommonUtil::fomatTime($model->updated_at)
            ],
        ],
    ]) ?>
	<h5>测评描述</h5>
	<?= $model->desc?>
	
	
	<div class="box box-success">
                <div class="box-header with-border">
                  <p class="box-title">测评问卷</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
  <p> <?= Html::a('创建问卷', ['create-question', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> </p>
  <p class="red">*注:测评开始后请不要随意修改问卷，否则将导致已经提交的结果与问卷不符</p>
    <?= GridView::widget([
        'dataProvider' => $questionData,
     //   'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            ['attribute'=>'type','value'=>function ($model){
                return CommonUtil::getDescByValue('report', 'type', $model->type);
                
            }],  
           ['attribute'=>'created_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
           ['attribute'=>'updated_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->updated_at);
           }],
         
           ['class' => 'yii\grid\ActionColumn','header'=>'操作',
               'template'=>'{update-question}{delete-question}',
               'buttons'=>[
                   'view-question'=>function ($url,$model,$key){
                   return Html::a('查看 |  ',$url);
                    },
                    'update-question'=>function ($url,$model,$key){
                    return Html::a('修改 |  ',$url);
                    },
                    'delete-question'=>function ($url,$model,$key){
                    return Html::a('删除   ',$url);
                    },
           
           ],
           ],
        ],
    ]); ?>
  </div>
  </div>
  
  <div class="box box-success">
                <div class="box-header with-border">
                  <p class="box-title">评价关系</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
  <p> <button class="btn btn-success" id="import-relation">导入评价关系</button> </p>
    <?= GridView::widget([
        'dataProvider' => $relationData,
     //   'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            ['attribute'=>'beuser.real_name',
              'label'=>'被评价人姓名'
             ],
            'work_number',
            ['attribute'=>'beuser.real_name',
                'label'=>'评价人姓名'
            ],
            'do_work_number',
            ['attribute'=>'type','value'=>function ($model){
                return CommonUtil::getDescByValue('report', 'type', $model->type);
            }],  
           ['attribute'=>'created_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
           ['attribute'=>'answer_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->answer_time);
           }],
         
           ['class' => 'yii\grid\ActionColumn','header'=>'操作',
               'template'=>'{view-answer}{update-question}{delete-relation}',
               'buttons'=>[
                   'view-answer'=>function ($url,$model,$key){
                   return Html::a('查看结果 |  ',$url);
                    },
//                     'update-question'=>function ($url,$model,$key){
//                     return Html::a('修改 |  ',$url);
//                     },
                    'delete-relation'=>function ($url,$model,$key){
                    return Html::a('删除   ',$url);
                    },
           
           ],
           ],
        ],
    ]); ?>
  </div>
  </div>
</div>
<!-- 导入当前环节状态 -->
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
               导入评价关系
            </h4>
         </div>
         <div class="modal-body">
            	
              <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-relation')?>" onsubmit="return check1()">						
        			<input type="file" value="文件" name="file" id="file" >	
        			<input type="hidden" name="_csrf" value="<?= yii::$app->request->csrfToken ?>">
        			<input type="hidden" name="reportid" value="<?= $model->id ?>">
        			<br>
        			<p class="red">*支持多次导入,只需导入新增加的评价关系</p>
        			<input type="submit" value="导入数据"  class="btn btn-success" >
        			
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
<script type="text/javascript">
$('#import-relation').click(function(){

	$('#relationModal').modal('show');
 });
function check1(){
	var importfrom = $("#file").val();
	if(importfrom==""){
		$("#errorImport1").html("<font color='red'>请选择导入的文件</font>");
		return false;
	}else{
		showWaiting('正在导入,请稍候...');
		return true;
	}
}
</script>
