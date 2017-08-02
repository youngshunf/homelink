<?php 

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>链家XVP管理后台 | 登录</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?= yii::getAlias('@web')?>/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
 
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= yii::getAlias('@web')?>/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= yii::getAlias('@web')?>/AdminLTE/plugins/iCheck/square/blue.css">
     <link rel="stylesheet" href="<?= yii::getAlias('@web')?>/css/common.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <style>
  </style>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo text-center">
        <p  >链家XVP</p>
        系统管理后台
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">登录</p>
          <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->textInput(['placeholder'=>'用户名/手机号'])->label('用户名') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('密码') ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div class="form-group text-center">
                    <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>

        <div class="social-auth-links text-center">
         
     
        </div><!-- /.social-auth-links -->

    

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?= yii::getAlias('@web')?>/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?= yii::getAlias('@web')?>/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?= yii::getAlias('@web')?>/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
