<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use common\models\UserOperation;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '模板消息', 'url' => ['template-']];
$this->params['breadcrumbs'][] = $this->title;
?>


 
    <div class="row">
    <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [ 
            ['attribute'=>'模板标识','value'=>$model->id],
           'name',       
            'template_id',
            'group_id',
            'group.group_name',
            'name',
            'url',         
            ['attribute'=>'创建时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->created_at)],        
        ],
    ]) ?>
    <h5>模板消息内容</h5>
    <?php foreach ($templateData as $v){?>
    <p><?= $v['key']?>.<?= $v['value']?></p>
    <?php }?>
     <p>
     <?= Html::a('发送模板消息', ['send-template-message','id'=>$model->id], ['class' => 'btn btn-success','id'=>'send-message']) ?>
        <?= Html::a('返回', ['template-message'], ['class' => 'btn btn-primary']) ?>
        
    </p>
    </div>

    </div>
<script>
$('#send-message').click(function(){
   showWaiting('正在发送,请稍后...');
});

 </script>
