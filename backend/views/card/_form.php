<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Card */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'district')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'shop')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'business_circle')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'building')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'sign')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
