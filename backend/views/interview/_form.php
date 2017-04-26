<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewDistrict */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-district-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'district_code')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'district_name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'assistant_number')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'assistant_name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'supervisor_number')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'supervisor_name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
