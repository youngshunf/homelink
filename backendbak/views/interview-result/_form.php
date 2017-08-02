<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewResult */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-result-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 12]) ?>

    <?= $form->field($model, 'id_code')->textInput(['maxlength' => 24]) ?>

    <?= $form->field($model, 'level')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'rec_work_number')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'rec_name')->textInput(['maxlength' => 12]) ?>

    <?= $form->field($model, 'interview_time')->textInput(['maxlength' => 12]) ?>

    <?= $form->field($model, 'interview_result')->textInput(['maxlength' => 48]) ?>

    <?= $form->field($model, 'train_result')->textInput(['maxlength' => 48]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => 128]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
