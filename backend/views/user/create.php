<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '新增管理员';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    
    <div class="row">
    <div class="col-md-2 ">
    </div>
     <div class="col-md-8 ">
     <div class="panel-white">
     <h3><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
    </div>
     <div class="col-md-2 ">
    </div>
    </div>
</div>
