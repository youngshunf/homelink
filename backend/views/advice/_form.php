<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Advice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_guid')->textInput(['maxlength' => 48]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => 20]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
