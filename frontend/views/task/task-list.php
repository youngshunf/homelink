<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

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

     <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

