<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'username')->textInput(['maxlength' => 48]) ?>
	<?= $form->field($model, 'pid')->dropDownList(ArrayHelper::map($adminUser, 'id', 'company'),['prompt'=>'无','prompt_val'=>'0']) ?>
    <?= $form->field($model, 'role_id')->dropDownList([ '9' => '普通用户','1'=>'MVP','2'=>'商圈经理','3'=>'总监','4'=>'副总']) ?>
       <?= $form->field($model, 'talent')->dropDownList([ 'MVP' => 'MVP','DVP'=>'DVP','SVP'=>'SVP']) ?>
    <?php if($model->isNewRecord){?>
	<?= $form->field($model, 'password')->passwordInput(['maxlength' => 32]) ?>
	<?= $form->field($model, 'password2')->passwordInput(['maxlength' => 32]) ?>
	<?php }?>
	
    <?= $form->field($model, 'real_name')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'nick')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'sex')->dropDownList([ '1' => '男','2'=>'女','0'=>'保密' ], ['prompt' => '']) ?>
	 <?= $form->field($model, 'age')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 48]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
