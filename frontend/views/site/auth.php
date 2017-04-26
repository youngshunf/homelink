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
  

    <div class="row">
    
        <div class="col-xs-12">
        <div class="panel-white">
        	  <h3><?= Html::encode($this->title) ?></h3>   			 
            <?php $form = ActiveForm::begin(['id' => 'auth-form']); ?>
                <?= $form->field($model, 'real_name')->label('姓名') ?>
                <?= $form->field($model, 'work_number')->textInput()->label('工号') ?>
        
             <div class="form-group center">
                    <?= Html::submitButton('立即验证', ['class' => 'btn btn-primary ', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            </div>
        </div>
     
    </div>
</div>
