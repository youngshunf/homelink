<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCard */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '名片管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
    <p class="red"> *不选择时间，导出全部名片</p>
    <form action="<?= Url::to(['export-card'])?>" method="post" id="export-form" >
     <input type="hidden" name="_csrf" value="<?= yii::$app->request->getCsrfToken()?>">
     <div class="input-group">
         <span class="input-group-addon">开始时间</span>
         <input type="date" class="form-control" name="startTime" id="startTime">
         <span class="input-group-addon">结束时间</span>
         <input type="date" class="form-control" name="endTime" id="endTime">
         <span class="input-group-addon btn btn-success" id="export">导出</span>
      </div>
    </form>
    <br>
    <?php Pjax::begin(['id'=>'card'])?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            'mobile',
            'email:email',
            'district',
             'shop',
             'business_circle',
             'building',  
             ['attribute'=>'创建时间','value'=>function ($model){
                 return CommonUtil::fomatTime($model->created_at);
             }],              
            ['class' => 'yii\grid\ActionColumn','header'=>'操作'],
        ],
    ]); ?>
    <?php Pjax::end()?>
</div>
<script>
 $(document).ready(function(){

	 $("#export").click(function(){
		    if($("#endTime").val()<$("#startTime").val()){
		        modalMsg("结束时间不能小于开始时间");
		    }
		    showWaiting("正在导出,请稍候...");
		    $("#export-form").submit();
		});
 });            

</script>