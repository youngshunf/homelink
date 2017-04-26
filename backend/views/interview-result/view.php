<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewResult */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '面试结果', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除这条记录吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'id_code',
            'level',
            'rec_work_number',
            'rec_name',
            'interview_time',
            'interview_result',
            'train_result',
            'status',
            'remark',
            ['attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s']
              ]
        ],
    ]) ?>

</div>
