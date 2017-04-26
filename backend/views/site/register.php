<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
  

    <div class="row">
    	<div class="col-lg-4"></div>
        <div class="col-lg-4">
        	  <h1><?= Html::encode($this->title) ?></h1>
   			 
            <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>
                <?= $form->field($model, 'email')->label('用户名') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('密码') ?>
                 <?= $form->field($model, 'password2')->passwordInput()->label('确认密码') ?>
                <?= $form->field($model, 'inviteCode')->label('邀请码') ?>
                <div class="form-group">
                    <?= Html::submitButton('注册', ['class' => 'btn btn-success', 'name' => 'register-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
