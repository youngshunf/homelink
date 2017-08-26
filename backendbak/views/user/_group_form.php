<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'action'=>'create-group',
        'options'=>['onsubmit'=>"return check()"]
    ]); ?>
   <div class="form-group">
   <label>分组名</label>
   <input type="text" name="group-name" id="group-name" class="form-control">
   </div>

    <div class="form-group center">
        <?= Html::submitButton( '提交', ['class' => 'btn btn-success' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
  function check(){
	    if(!$("#group-name").val()){
	        modalMsg('请填写分组名');
	        return false;
	    }

	    showWaiting('正在提交,请稍候...');
	    return true;
	    
  }
 </script>