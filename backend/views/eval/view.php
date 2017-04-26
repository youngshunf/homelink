<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '评价管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->qid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->qid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此项目?',
                'method' => 'post',
            ],
        ]) ?>
         <?= Html::a('查看评价结果', ['view-result', 'id' => $model->qid], ['class' => 'btn btn-info']) ?>
          <?= Html::a('返回', ['index'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'content:ntext',
            ['attribute'=>'创建时间','value'=>CommonUtil::fomatTime($model->created_at)]
          
        ],
    ]) ?>
    
    <h3>评价项</h3>
      <?= GridView::widget([
        'dataProvider' => $optData,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',   
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
    		'title',
    		['attribute'=>'题型','value'=>function ($opt){
    		   return CommonUtil::getDescByValue('option', 'type', $opt->type);
    		}],
    		['attribute'=>'选项',
    		    'format' => 'html',
    		    'value'=>function ($opt){
    		   $result="";
    		   if(!empty($opt->content)){
    		   $options=json_decode($opt->content,true);
    		   foreach ($options as $k=> $o){   		       
    		       $result.='<p>'.intval($k+1).':'.$o.'</p>';
    		   }
    		   }
    		   return $result;
    		}],
    		['attribute'=>'创建时间','value'=>function ($opt){
    		    return CommonUtil::fomatTime($opt->created_at);
    		}],
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{option-delete}',
	             'buttons'=>[
					'option-delete'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除选项', 'data-confirm'=>'是否确定删除该选项？'] );
					},					
				]
           	],
        ],
    ]); ?>
    <a class="btn btn-success " id="option">新增评价项</a>
    
    <div class="modal fade" id="addoption" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               增加评价选项
            </h4>
         </div>
         <div class="modal-body">            	
            <form action="<?=Url::to(['add-option'])?>" method="post" id="option-form" onsubmit="return check()">
           <input type="hidden" name="qid" value="<?= $model->qid?>">
            <div class="form-group">
                <label class="label-control">评价内容</label>
               <textarea rows="3"  name="title" id="title" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label class="label-control">评价类型</label>
               <select name="type" id="type" class="form-control">
               <option value="0">单选题</option>
               <option value="1">多选题</option>
               <option value="2">判断题</option>
               <option value="3">简答题</option>
               </select>
            </div>
            <div id="optArr-container" >
            <div id="optArr">
            <div class="form-group">
                <label class="label-control">选项1</label>                
             <input type="text" name="optArr[]" class="form-control">                                  
            </div>
            </div>
               <p class="pull-right"><a id="addOpt" href="javascript:;"><span class="glyphicon glyphicon-plus " style="color: red;font-size:26px"> </span></a></p>
                <p class="clear"></p>
                </div>
                <p class="center"><button type="submit" class="btn btn-info">提交</button></p>
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
    
</div>
<script>
$('#option').click(function(){
    $("#addoption").modal('show');
});
var i=1;
$("#addOpt").click(function(){
	i++;
    var innerHtml='<div class="form-group">\
        <label class="label-control">选项'+i+'</label><input type="text" name="optArr[]" class="form-control"></div>';
    $("#optArr").append(innerHtml);
});

$("#type").click(function(){
   var type=$(this).val();
   if(type==0||type==1){
	    $("#optArr-container").removeClass('hide');
   }else{
	   $("#optArr-container").addClass('hide');
   }
});

function check(){
	if(!$("#title").val()){
	    modalMsg("请填写评价内容");
	    return false;
	}
	var opt=0;
	 $("input[name='optArr[]']").each(function(index,item){
		 if($(this).val()){
			 opt++;
		    }
	 });
	 var type=$("#type").val();
	 if(type==0||type==1){
		    if(opt==0){
			    modalMsg("单选或多选至少要有一个选项");
		        return false;
		    }
	 }

	 showWaiting("正在提交,请稍候...");
	 return true;
}

  </script>