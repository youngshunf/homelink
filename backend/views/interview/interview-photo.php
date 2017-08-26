<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchInterviewDistrict */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '申诉照片';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-district-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $data,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'district_code',
            'district_name',
            'work_number',
            'name',
            'year_month',
                ['attribute'=>'照片类型',
                'value'=>function ($model){
                return $model->type==1?'总监签字照片':'面试现场照片';
                }
                ],
                ['attribute'=>'面试现场照片',
                'format'=>'html',
                'value'=>function ($model){
                return Html::img(yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo,['class'=>'img-responsive']);
                }
                ],
           
            // 'created_at',
//             ['class' => 'yii\grid\ActionColumn',
//                'header'=>'操作',
                
//             ],
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
            	
              <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-data')?>" onsubmit="return check1()">						
        			<input type="file" value="文件" name="relation" id="relation" >	
        			<br>
        			<p class="red">*导入新的数据会将会覆盖以前的大区数据，请确保大区编号一致</p>
        			<input type="submit" value="导入大区数据"  class="btn btn-primary" >
        			
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
