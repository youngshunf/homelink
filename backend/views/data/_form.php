<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ResultsData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="results-data-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'work_number')->textInput(['maxlength' => 32]) ?>
      <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'big_district')->textInput() ?>
      <?= $form->field($model, 'business_district')->textInput() ?>
        <?= $form->field($model, 'total_score')->textInput() ?>
    <?= $form->field($model, 'honor_score')->textInput() ?>

    <?= $form->field($model, 'co_index')->textInput() ?>

    <?= $form->field($model, 'teach_score')->textInput() ?>

    <?= $form->field($model, 'results')->textInput() ?>

    <?= $form->field($model, 'youmi')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => 20]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
