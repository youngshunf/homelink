<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use common\models\UserOperation;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->real_name;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white"> 

 <div class="row">
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
         <p><b>用户名:</b><?=$model->username?></p>
        <p><b>姓名:</b><?=$model->real_name?></p>
          <p><b>用户角色:</b><?=CommonUtil::getDescByValue('user', 'role_id', $model->role_id)?></p>
        <p><b>电话:</b><?=$model->mobile?></p>
        <p><b>邮箱:</b><?=$model->email?></p>
        <p><b>大区:</b><?=$model->district?></p>
        <p><b>店面:</b><?=$model->shop?></p>
        <?php if($model->puser){?>
        <p><b>城市公司:</b><?=$model->puser->company?></p>
        <?php }?>
    </div>
    </div>
    <br>
    <div class="row">
    <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [    
            'age',
            'talent',
            'business_circle',
            'building',
            'address',
            'sign:ntext',         
            ['attribute'=>'创建时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->updated_at)],        
        ],
    ]) ?>
     <p>
        <?= Html::a('返回', ['index'], ['class' => 'btn btn-primary']) ?>
        
    </p>
    </div>

    </div>
</div>
