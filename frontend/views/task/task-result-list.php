<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchTask */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="panel-white">


     <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_result_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

</div>
