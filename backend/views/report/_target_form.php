<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use wenyuan\ueditor\Ueditor;
use common\models\TaskStep;

/* @var $this yii\web\View */
/* @var $model common\models\Report */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form ">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
     <?= $form->field($model, 'weight')->textInput(['maxlength' => 255]) ?>
    
    
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
