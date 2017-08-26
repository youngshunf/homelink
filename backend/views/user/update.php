<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '修改';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    
    <div class="row">
    <div class="col-md-2 ">
    </div>
     <div class="col-md-8 ">
    <?= $this->render('_user_form', [
        'model' => $model,
        'adminUser'=>$adminUser
    ]) ?>
    </div>
     <div class="col-md-2 ">
    </div>
    </div>
</div>
