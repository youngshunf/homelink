<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->title = '修改登录密码';

?>

<div class="panel-white">

   <form action="<?= Url::to(['/interview-supervisor/change-password-do'])?>" method="post" onsubmit="return check()">
    <div class="form-group ">
    <label class="control-label">旧密码:</label>
    <input type="password" name="oldPwd" class="form-control">
    </div>
    <div class="form-group ">
    <label class="control-label">请输入新密码:</label>
    <input type="password" name="pwd1" class="form-control">
    </div>
     <div class="form-group ">
    <label class="control-label">确认新密码:</label>
    <input type="password" name="pwd2" class="form-control">
    </div>
   


    <div class="form-group ">
     <button type="submit" class="btn btn-success" > 提交</button>
    </div>
</form>

</div>
<script>
 function check(){
   if(!$('input[name=oldPwd').val()){
      modalMsg('请输入旧密码');
      return false;
   }
   if(!$('input[name=pwd1').val()){
	      modalMsg('请输入新密码');
	      return false;
	   }
   if(!$('input[name=pwd2').val()){
	      modalMsg('请再次输入新密码');
	      return false;
	   }
   if($('input[name=pwd1').val() !=$('input[name=pwd2').val()){
	      modalMsg('两次密码不一致');
	      return false;
	   }
   return true;
 }
</script>