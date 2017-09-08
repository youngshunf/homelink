<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchReportResult */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '测评报告';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-result-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('上传测评报告', ['create'], ['class' => 'btn btn-success']) ?>
        <button class="btn btn-success" id="import-report">批量导入测评报告</button>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'user.real_name',
            'work_number',
            'name',
            ['attribute'=>'report_time','value'=>function ($model){
                return CommonUtil::fomatTime($model->report_time);
             }],
            ['attribute'=>'created_at','value'=>function ($model){
                return CommonUtil::fomatTime($model->created_at);
                }],

            ['class' => 'yii\grid\ActionColumn','header'=>'操作',
                'template'=>'{view}{update}{delete}{top}',
                'buttons'=>[
                    'view'=>function ($url,$model,$key){
                    return Html::a('查看 | ',$url);
            },
            'update'=>function ($url,$model,$key){
            return Html::a('修改 | ',$url);
            },
            'delete'=>function ($url,$model,$key){
            return Html::a('删除 | ',$url);
            },
            
            ],
            ],
        ],
    ]); ?>

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
               批量导入测评报告
            </h4>
         </div>
         <div class="modal-body">
            	
              <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-report')?>" onsubmit="return check1()">						
        			<label>选择Excel</label>
        			<input type="file" value="文件" name="file" id="file" >	
        			<input type="hidden" name="_csrf" value="<?= yii::$app->request->csrfToken ?>">
        			<br>
        			<label>选择zip</label>
        			<input type="file" value="文件" name="zip" id="zip" >	
        			<p class="center">
        			<input type="submit" value="导入报告"  class="btn btn-success" >
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
</div><!-- /.modal -->

<script type="text/javascript">
$('#import-report').click(function(){

	$('#relationModal').modal('show');
 });
function check1(){
	var importfrom = $("#file").val();
	if(importfrom==""){
		$("#errorImport1").html("<font color='red'>请选择导入的文件</font>");
		return false;
	}
   
	
		showWaiting('正在导入,请稍候...');
		return true;
	
}
</script>
