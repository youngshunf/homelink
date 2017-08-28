<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\ReportResult */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Report Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-result-view">


    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此记录?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user.real_name',
            'work_number',
            'name',
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
   <h5>报告描述</h5>
   <div class="panel-white">
   <?= $model->desc?>
   </div>
   
   <h5>测评报告</h5>
   <div class="panel-white">
   <a href="<?= yii::$app->params['fileUrl'].$model->path.$model->photo?>">下载</a>
   </div>
</div>
