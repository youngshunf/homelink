<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.mui-input-group .form-group label{
	width:35%;
}
.mui-input-group .form-group  .form-control{
	width:60%;
	display:inline-block;
}
.form-group {
    margin-bottom: 5px;
    border-bottom: 1px solid #f5f5f5;
}
</style>

<div class="wish-form">

    <?php $form = ActiveForm::begin(['id'=>'user-form','options' => ['onsubmit'=>'return check()','class'=>'mui-input-group']]); ?>

   <?= $form->field($model, 'real_name')->textInput(['maxlength' => 30]) ?>
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 30]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 30]) ?>
   <?= $form->field($model, 'sex')->dropDownList(['1'=>'男','2'=>'女']) ?>
    <?= $form->field($model, 'age')->textInput(['type'=>'number']) ?>
       <?= $form->field($model, 'province')->textInput(['maxlength' => 20]) ?>
        <?= $form->field($model, 'city')->textInput(['maxlength' => 20]) ?>
    <div class="form-group center bottom-btn">
        <?= Html::submitButton( '提交' , ['class' => 'btn btn-success btn-block btn-lg' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
  function check(){
	  if( $(".has-error").length>0){		
			modalMsg("请填写正确再提交!");
		    return false;
		}	
	  

	    var e=0;
	    $("input[type=text]").each(function(){
	        if(!$(this).val()){
	            e++;
	        }
	    });
	    if(e>0){
	    	modalMsg("请填写完整再提交!");
	        return false;
	    }

	    showWaiting("正在提交,请稍后...");
	    return true;
  }
</script>
