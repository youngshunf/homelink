<?php

use yii\helpers\Html;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = '下级任务';

?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= ListView::widget([
    'dataProvider'=>$dataProvider,
    'itemView'=>'_result_item',
    'layout'=>"{items}\n{pager}"
    ]
    );?>

</div>
