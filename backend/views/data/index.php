<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchResultsData */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '数据管理';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/Font-Awesome-3.2.1/css/font-awesome.min.css');
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
    <button class="btn btn-success" id="import-data">导入数据</button>
    <button class="btn btn-primary"  id="import-relation">导入查询关系</button>
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'work_number',
            'name',          
            'big_district',
            'business_district',
            'year_month',
            'honor_score',
            'co_index',
             'teach_score',
             'results',     
            'rank',
          

            ['class' => 'yii\grid\ActionColumn','header'=>'操作'],
        ],
    ]); ?>

</div>

  <!-- 模态框（Modal）导入数据 -->
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
               导入数据
            </h4>
         </div>
         <div class="modal-body">
            	
    <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-data')?>" onsubmit="return check()">
			<div class="form-group">
			<label class="label-control">导入月份:</label>
			<input type="date"  name="yearMonth" id="yearMonth" class="form-control">	
			</div>
			
			<br>		
			<input type="file" value="文件" name="importfrom" id="importfrom" >	
			<br>
			<input type="submit" value="导入数据"  class="btn btn-success" >
		  <p id="errorImport"></p>		
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
function check(){
	var importfrom = $("#importfrom").val();
	var yearMonth=$('#yearMonth').val();
	if(!yearMonth){
		$("#errorImport").html("<font color='red'>请选择导入月份</font>");
		return false;
	}
	if(importfrom==""){
		$("#errorImport").html("<font color='red'>请选择导入的文件</font>");
		return false;
	}else{
		showWaiting('正在导入,请稍候...');
		return true;
	}
}
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
 $('#import-data').click(function(){
	   $('#dataModal').modal('show');
 });

 $('#import-relation').click(function(){
	$('#relationModal').modal('show');
 });
</script>