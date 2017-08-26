<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewRegister */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Interview Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-register-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_guid',
            'district_code',
            'district_name',
            'activity_id',
            'year_month',
            'work_number',
            'name',
            'mobile',
            'signup_result',
            'interview_result',
            'remark',
            'is_appeal',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
