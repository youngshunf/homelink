<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use common\models\UserOperation;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white"> 

 <div class="row">
  <p>
        <?= Html::a('修改', ['update-admin', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('重置密码', ['reset-password', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要重置密码吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="col-md-6">
    <?php if(!empty($model->photo)){?>
        <img alt="头像" src="<?=yii::getAlias('@avatar').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
    <?php }elseif (!empty($model->img_path)){?>
    <img alt="头像" src="<?=$model->img_path?>" class="img-responsive">
   <?php }else{?>
   <img alt="头像" src="<?=yii::getAlias('@avatar')?>/unknown.jpg" class="img-responsive">
   <?php }?>
    </div>
    <div class="col-md-6">
        <p><b>用户名: </b><?=$model->username?></p>
        <p><b>姓名: </b><?=$model->real_name?></p>
        <p><b>电话: </b><?=$model->mobile?></p>
        <p><b>邮箱: </b><?=$model->email?></p>
        <p><b>分公司: </b><?=$model->company?></p>
        <p><b>角色: </b><?=CommonUtil::getDescByValue('admin_user', 'role_id', $model->role_id)?></p>
        <p><b>创建时间: </b><?=CommonUtil::fomatTime($model->created_at)?></p>
        <p><b>更新时间: </b><?=CommonUtil::fomatTime($model->updated_at)?></p>
        <p><b>最后登录IP: </b><?=$model->last_ip?></p>
        <p><b>最后登录时间: </b><?=CommonUtil::fomatTime($model->last_time)?></p>
    </div>
    </div>
    <br>
    <div class="row">
    <div class="col-md-12">
     <p class="center">
        <?= Html::a('返回', ['index'], ['class' => 'btn btn-primary']) ?>
        
    </p>
    </div>

    </div>
</div>
