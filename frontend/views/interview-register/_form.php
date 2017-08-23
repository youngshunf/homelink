<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewRegister */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-register-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'district_code')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'district_name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'activity_id')->textInput() ?>

    <?= $form->field($model, 'year_month')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'work_number')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'signup_result')->textInput() ?>

    <?= $form->field($model, 'interview_result')->textInput() ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'is_appeal')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
