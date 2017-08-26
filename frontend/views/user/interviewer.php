<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的面试';
?>
<style>
.mui-table-view:before{
	background:none
}
</style>
<h5>
<?= $this->title?>
</h5>

    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_interviewer_item',            
           'layout'=>"{items}\n{pager}"
      ])?>



