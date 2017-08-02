<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">


    <p>
        <?= Html::a('修改', ['update', 'id' => $model->activity_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->activity_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此活动吗?',
                'method' => 'post',
            ],
        ]) ?>
        <a class="btn btn-info" href="<?= Url::to(['index'])?>">返回</a>
           <a class="btn btn-warning" href="#register-field">查看报名结果</a>
           <a class="btn btn-success" href="<?= Url::to(['export-register','activity_id'=>$model->activity_id])?>">导出报名结果</a>
       
    </p>
<div class="row">
    <div class="col-md-6">
        <img src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>"  class="img-responsive"/>
    </div>
    
<div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [      
            'title',         
            ['attribute'=>'start_time','value'=>CommonUtil::fomatTime($model->start_time)],
            ['attribute'=>'end_time','value'=>CommonUtil::fomatTime($model->end_time)],
          'max_number',
            ['attribute'=>'created_at','value'=>CommonUtil::fomatTime($model->created_at)],       
        ],
    ]) ?>
    </div>
</div>
    <div class="row">
          <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [         
                   ['attribute'=>'scope','value'=> CommonUtil::getDescByValue('activity', 'scope', $model->scope)
           ],
           ['attribute'=>'type','value'=>
               CommonUtil::getDescByValue('activity', 'type', $model->type)
           ],
            ['attribute'=>'sign_start_time','value'=>
                CommonUtil::fomatTime($model->sign_start_time)
           ],
           ['attribute'=>'sign_end_time','value'=>
               CommonUtil::fomatTime($model->sign_end_time)
           ],
            'province',
            'city',
            'address',
            'max_number',
              ['attribute'=>'sign_end_time','value'=>
               CommonUtil::fomatTime($model->sign_end_time)
           ],
            'shop',
             ['attribute'=>'outer_link','label'=>'外部活动连接'  ],
            ['attribute'=>'created_at','value'=>
            CommonUtil::fomatTime($model->created_at)
            ],
            ['attribute'=>'updated_at','value'=>
            CommonUtil::fomatTime($model->updated_at)
            ],
      
        ],
    ]) ?>
    
  
    <h3>活动介绍</h3>
    <?= $model->content?>

    <h5>报名结果</h5>
    <p><a class="btn btn-success pull-right" href="<?= Url::to(['export-register','activity_id'=>$model->activity_id])?>">导出报名结果</a></p>
  <p class="clear"></p>
    <?php Pjax::begin(['id'=>'register-field'])?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
     //   'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'work_number',
            'name',
            'mobile',
            'email',

            ['attribute'=>'created_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],    
           ['attribute'=>'是否签到','value'=>function ($model){
               return $model->is_sign==0?"否":"是";
           }],
           ['attribute'=>'签到时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->sign_time);
           }],
           ['attribute'=>'签到人','value'=>function ($model){
               return $model['manager']['real_name'];
           }],
        ],
    ]); ?>
    <?php Pjax::end()?>
        </div>
</div>
</div>
