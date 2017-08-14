<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">


    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            ['attribute'=>'start_time','value'=>
                CommonUtil::fomatTime($model->start_time)
            ],
            ['attribute'=>'end_time','value'=>
                CommonUtil::fomatTime($model->end_time)
            ],
            ['attribute'=>'report_time','value'=>
                CommonUtil::fomatTime($model->report_time)
            ],
            ['attribute'=>'created_at','value'=>
                CommonUtil::fomatTime($model->created_at)
            ],
            ['attribute'=>'updated_at','value'=>
                CommonUtil::fomatTime($model->updated_at)
            ],
        ],
    ]) ?>
	<h5>测评描述</h5>
	<?= $model->desc?>
</div>
