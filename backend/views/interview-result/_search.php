<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchInterviewResult */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-result-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'id_code') ?>

    <?= $form->field($model, 'level') ?>

    <?= $form->field($model, 'rec_work_number') ?>

    <?php // echo $form->field($model, 'rec_name') ?>

    <?php // echo $form->field($model, 'interview_time') ?>

    <?php // echo $form->field($model, 'interview_result') ?>

    <?php // echo $form->field($model, 'train_result') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
