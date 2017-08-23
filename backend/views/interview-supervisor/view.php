<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewDistrict */

$this->title = $model->district_name;
$this->params['breadcrumbs'][] = ['label' => '优才面试', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-district-view">


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
            'district_code',
            'district_name',
            'assistant_number',
            'assistant_name',
            'supervisor_number',
            'supervisor_name',
            'created_at',
        ],
    ]) ?>

 
</div>
