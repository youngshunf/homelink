<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchTask */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MVP成长记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
     <a href="javascript:;" class="btn btn-success" id="import-data">导入数据</a>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
           'user.name',
            'work_number',
            'items',
           'item_time',
           'score',
           'classname',
       /*      ['attribute'=>'item_time',
            'value'=>function ($model){
            return date('Y-m-d',strtotime($model->item_time));
            }
            ], */

            ['class' => 'yii\grid\ActionColumn','header'=>'操作',
            'template'=>'{delete-grow}',
            'buttons'=>[
                'delete-grow'=>function ($url,$model,$key){
                return Html::a('删除',$url,['class'=>'btn btn-danger']);
            }
            ],
            ],
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
               导入MVP成长记录
            </h4>
         </div>
         <div class="modal-body">
            	
              <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-growdata')?>" onsubmit="return check1()">						
        			<input type="hidden"  name="_csrf" value="<?= yii::$app->request->csrfToken?>">
        			<input type="file" value="文件" name="relation" id="relation" >	
        			<br>
        			<input type="submit" value="导入查询关系"  class="btn btn-primary" >
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