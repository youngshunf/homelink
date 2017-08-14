<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '身份未验证';
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <div class="alert alert-danger">
      <h4>您的身份未被验证,不能进行此操作,请验证身份后重试</h4>
      <div class="center">
      
      <?php if(!yii::$app->user->isGuest){?>
      <a class="btn btn-success btn-lg btn-block" href="<?= Url::to(['auth','openid'=>yii::$app->user->identity->openid])?>">立即验证</a>
      <?php }?>
      </div>
    </div>

 

</div>
