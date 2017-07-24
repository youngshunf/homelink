<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '您没有权限';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="alert alert-danger">
      <h2>您没有权限访问此页面</h2>
      <div class="center">
      
     
      <a class="btn btn-info" href="<?= yii::$app->request->referrer?>">返回</a>
      
      </div>
    </div>

 

</div>
