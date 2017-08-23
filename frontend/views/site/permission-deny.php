<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '您没有权限';
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <div class="alert alert-danger">
      <h4>您没有权限访问此页面</h4>
      <div class="center">
      
     
      <a class="btn btn-success" href="<?= yii::$app->request->referrer?>">返回</a>
      
      </div>
    </div>

 

</div>
