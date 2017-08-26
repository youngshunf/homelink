<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchInterviewDistrict */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-district-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'district_code') ?>

    <?= $form->field($model, 'district_name') ?>

    <?= $form->field($model, 'assistant_number') ?>

    <?= $form->field($model, 'assistant_name') ?>

    <?php // echo $form->field($model, 'supervisor_number') ?>

    <?php // echo $form->field($model, 'supervisor_name') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
