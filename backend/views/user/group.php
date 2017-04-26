<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户分组';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
  <p>
  <a class="btn btn-success" href="javascript:;" id="create-group">新建分组</a>
  <button class="btn btn-info"  id="import-btn">导入分组</button>
  </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',   
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'id',
    		'group_name',
    		
    	
            ['attribute'=>'创建时间','value'=>function ($model){
      
            	return CommonUtil::fomatTime($model->created_at);
            }],
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-group}{edit-group}{delete-group}',
	             'buttons'=>[
					'view-group'=>function ($url,$model,$key){
	                     return  Html::a('查看分组用户', $url, ['class' => 'btn btn-primary'] );
					},
				/* 	'edit-group'=>function ($url,$model,$key){
				
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '编辑分组'] );					       
				
				
					   
				},
					'delete-group'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除分组', 'data-confirm'=>'是否确定删除该分组？'] );
					}, */
					
				]
           	],
        ],
    ]); ?>

</div>

<div class="modal fade" id="addgroup" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               新建分组
            </h4>
         </div>
         <div class="modal-body">            	
             <?= $this->render('_group_form') ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->

<div class="modal fade" id="import-group" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
              导入分组
            </h4>
         </div>
         <div class="modal-body">            	
            <form enctype="multipart/form-data"  action="<?= Url::to(['import-group'])?>" method="post" onsubmit="return checkImport()">
            <input type="hidden" name="_csrf" value="<?= yii::$app->getRequest()->getCsrfToken()?>">
            <input type="file" name="group"  id="groupFile">
            <br>
            <div class="center">
            <button type="submit"  class="btn btn-info">提交</button>
            </div>
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
$('#create-group').click(function(){
    $('#addgroup').modal('show');
});
$('#import-btn').click(function(){
    $("#import-group").modal('show');    
});

function checkImport(){
	if(!$("#groupFile").val()){
	    modalMsg("请选择分组文件");
	    return false;
	}

	   showWaiting("正在提交,请稍后...");
	return true;
}

</script>