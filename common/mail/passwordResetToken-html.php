<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<div class="password-reset">
    <p>您好,<?= Html::encode($user->username) ?>:</p>
    <p>您在国民旅游环球俱乐部申请了密码重置,请点击下面的连接进行密码重置操作,该连接两个小时后失效，如失效请重新申请:</p>

    <p><?= Html::a(Html::encode('重置密码'), $resetLink,['class'=>'btn btn-success']) ?></p>
</div>
