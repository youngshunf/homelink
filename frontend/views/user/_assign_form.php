<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewResult */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-result-form">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->field($model, 'time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'interview_user')->dropDownList(ArrayHelper::map($user, 'user_guid', 'name'))?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
