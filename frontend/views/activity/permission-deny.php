<?php

use yii\helpers\Html;

$this->title = '您没有签到权限';
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <div class="alert alert-danger">
      <h2>您不是签到管理员,无权进行签到操作</h2>
    </div>

 

</div>
