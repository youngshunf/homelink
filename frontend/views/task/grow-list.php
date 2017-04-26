<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchTask */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title="任务列表";
?>
<style>
.mui-table-view li:first-child {
  position: relative;
  overflow: hidden;
  background: #EEEEF2;
}
.mui-table-view-cell {
  padding: 5px 8px;
}
</style>
<form action="<?= Url::to(['search-mvp'])?>" method="post"  id="search-form">
<input type="hidden" name="_csrf" value="<?= yii::$app->request->csrfToken?>">
  <div class="input-group">
         <span class="input-group-addon">姓名</span>
         <input type="text" class="form-control" name="name" >
         <span class="input-group-addon">工号</span>
          <input type="text" class="form-control" name="work_number">
          <span class="input-group-addon btn btn-primary"  id="search">搜索</span>
      </div>

</form>
     <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_grow_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

<script>
  $('#search').click(function(){
	    if(!$('input[name=name]').val()&&!$('input[name=work_number]').val()){
	        modalMsg('请至少输入一项搜索!');
	        return false;
	    }

	    showWaiting('正在搜索,请稍候...');
	    $('#search-form').submit();
  });

  </script>