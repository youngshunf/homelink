<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\TaskStep;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '任务管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$steps=TaskStep::find()->andWhere(['task_id'=>$model->id])->orderBy('step asc')->all();
$maxStep=count($steps);
?>
<div class="panel-white">

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除此项目吗?',
                'method' => 'post',
            ],
        ]) ?>
          <?= Html::a('查看结果', ['task-result', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="row">
    <div class="col-md-6">
    <img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
    </div>
    
  <div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'standard',
            'count_exec',
              ['attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i;s']
            ],
            ['attribute'=>'updated_at',
            'format'=>['date','php:Y-m-d H:i;s']
            ],
        ],
    ]) ?>
    </div>
   </div>
   
      <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">任务进度</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"> 
    <div id="steps">
    <?php if($maxStep>0 & $maxStep != $model->current_step){?>
    <a class="btn btn-success " href="<?= Url::to(['next-step','task_id'=>$model->id])?>">进入下一环节</a>
    <?php }elseif($maxStep>0 & $maxStep == $model->current_step){?>
     <a class="btn btn-success " href="<?= Url::to(['next-step','task_id'=>$model->id])?>">结束任务</a>
    <?php }?>
     <Steps :current="current">
     <div style="padding:15px">
      <?php 
      foreach ($steps as $v){
      ?>
        <Step title="<?= $v->title ?> - <?= CommonUtil::getDescByValue('step', 'status', $v->status) ?>" content="<?= $v->content?>"></Step>
       <?php }?>
      
     </Steps>
         </div>
        </div>
    </div>
     <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">任务要求</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"> 
    <?= $model->requirement?>
    </div>
    </div>
</div>

<script type="text/javascript">
new Vue({
    el: '#main',
    data:function(){
        return {
        	current: <?= $model->current_step -1;?>
        }
    },
    methods: {
        
    }
})
</script>
