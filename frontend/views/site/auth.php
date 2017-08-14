<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '身份验证';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
  

        <div class="panel-white">
        	  <h5><?= Html::encode($this->title) ?></h5>   			 
            <?php $form = ActiveForm::begin(['id' => 'auth-form']); ?>
                <?= $form->field($model, 'real_name')->label('姓名') ?>
                <?= $form->field($model, 'work_number')->textInput()->label('工号') ?>
        	    <?= $form->field($model, 'mobile')->textInput() ?>
             <div class="form-group center">
                    <?= Html::submitButton('立即验证', ['class' => 'btn btn-success btn-lg btn-block ', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            </div>
</div>
