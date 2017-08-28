<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '测评统计';
?>
<style>
.mui-table-view:before{
	background:none
}
</style>

    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_report_item',            
           'layout'=>"{items}\n{pager}"
      ])?>



