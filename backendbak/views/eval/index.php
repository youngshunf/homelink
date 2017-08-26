<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchQuestion */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评价管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增评价', ['create'], ['class' => 'btn btn-success']) ?>
        <button class="btn btn-primary"  id="import-relation">导入查询关系</button>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'title',
            'content:ntext',
            ['attribute'=>'创建时间','value'=>function($model){
                return CommonUtil::fomatTime($model->created_at);
            }],          
            // 'updated_at',

             [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view}{update}{delete}{view-result}',
	             'buttons'=>[
					'view-result'=>function ($url,$model,$key){
					return  Html::a('查看结果', $url, ['title' => '查看结果','class'=>'btn btn-info'] );
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
            	
              <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-relation')?>" onsubmit="return check1()">						
        			<input type="file" value="文件" name="relation" id="relation" >	
        			<br>
        			<input type="submit" value="导入查询关系"  class="btn btn-primary" >
        			<span class="red">*导入新的数据会将以前的数据全部覆盖,请确保新的数据是完整数据</span>
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
$('#import-relation').click(function(){
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