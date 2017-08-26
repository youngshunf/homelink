<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'username',['enableAjaxValidation'=>true])->textInput(['maxlength' => 48]) ?>
	<?= $form->field($model, 'company')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'role_id')->dropDownList([ '99' => '系统管理员','98'=>'普通管理员']) ?>
    <?php if($model->isNewRecord){?>
	<?= $form->field($model, 'password')->passwordInput(['maxlength' => 32]) ?>
	<?= $form->field($model, 'password2')->passwordInput(['maxlength' => 32]) ?>
	<?php }?>
	
    <?= $form->field($model, 'real_name')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'nick')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'sex')->dropDownList([ 'm' => '男','f'=>'女','n/a'=>'保密' ], ['prompt' => '']) ?>

    <?= $form->field($model, 'mobile',['enableAjaxValidation'=>true])->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'email',['enableAjaxValidation'=>true])->textInput(['maxlength' => 48]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
