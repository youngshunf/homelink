<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchInterviewRegister */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-register-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <?php // echo $form->field($model, 'year_month') ?>
    <?= $form->field($model, 'name',['options'=>['class'=>'input-group']])->label('姓名',['class'=>'input-group-addon']) ?>
    <br>
    <?= $form->field($model, 'work_number',['options'=>['class'=>'input-group']])->label('工号',['class'=>'input-group-addon']) ?>
    <br>
    <?= $form->field($model, 'mobile',['options'=>['class'=>'input-group']])->label('电话',['class'=>'input-group-addon']) ?>
    <br>
    <?php  //echo $form->field($model, 'work_number') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'signup_result') ?>

    <?php // echo $form->field($model, 'interview_result') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'is_appeal') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group center">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
