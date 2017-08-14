<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchActivity */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动日历';
$this->registerJsFile('@web/js/date.js');
?>
<style>
.mui-table-view{
	margin-bottom:10px
}

.wrap > .container {
  padding: 0;
}
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
  position: relative;
  min-height: 1px;
  padding-right: 5px;
  padding-left: 0px;
}
.row {
  margin-left: 0px; 
}

a{
	color:#000;
}
.time{
    margin-top:8px;
	padding-top:8px;
	border-top:1px solid grey;
}
</style>
<!-- 
<form action="<?= Url::to(['search-time'])?>" method="post"  id="time-form">
    <div class="input-group">
    <input type="hidden" name="_csrf" value="<?= yii::$app->request->getCsrfToken()?>">  
         <input type="text"  class="form-control kbtn" name="time" id="time"  value="<?= $yearMonth?>">
         <span class="input-group-addon btn btn-success" id="submit">搜索</span>
      </div>
      <div id="datePlugin"></div>
     </form>
 -->     
     <?php if(!empty($topData)){
     
         echo 
         ListView::widget([
        'dataProvider' => $topData,
          'itemView'=>'_item',            
           'layout'=>"{items}"
         ]); 
     }?>
     
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
          'itemView'=>'_item',            
           'layout'=>"{items}\n{pager}"
    ]); ?>

<script>
$("#submit").click(function(){
	if(!$("#time").val()){
	    modalMsg("请选择日期");
	    return false;
	}
	showWaiting("正在查询,请稍候....");
	$("#time-form").submit();
	return true;
});

$(function(){
	$('#time').date();
});
 </script>