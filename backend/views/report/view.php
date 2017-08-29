<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">


    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            ['attribute'=>'start_time','value'=>
                CommonUtil::fomatTime($model->start_time)
            ],
            ['attribute'=>'end_time','value'=>
                CommonUtil::fomatTime($model->end_time)
            ],
            ['attribute'=>'report_time','value'=>
                CommonUtil::fomatTime($model->report_time)
            ],
            ['attribute'=>'created_at','value'=>
                CommonUtil::fomatTime($model->created_at)
            ],
            ['attribute'=>'updated_at','value'=>
                CommonUtil::fomatTime($model->updated_at)
            ],
        ],
    ]) ?>
	<h5>测评描述</h5>
	<?= $model->desc?>
	
	
	<div class="box box-success">
                <div class="box-header with-border">
                  <p class="box-title">测评问卷</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
  <p> <?= Html::a('创建问卷', ['create-question', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> </p>
  <p class="red">*注:测评开始后请不要随意修改问卷，否则将导致已经提交的结果与问卷不符</p>
    <?= GridView::widget([
        'dataProvider' => $questionData,
     //   'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            ['attribute'=>'type','value'=>function ($model){
                return CommonUtil::getDescByValue('report', 'type', $model->type);
                
            }],  
           ['attribute'=>'created_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
           ['attribute'=>'updated_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->updated_at);
           }],
         
           ['class' => 'yii\grid\ActionColumn','header'=>'操作',
               'template'=>'{update-question}{delete-question}',
               'buttons'=>[
                   'view-question'=>function ($url,$model,$key){
                   return Html::a('查看 |  ',$url);
                    },
                    'update-question'=>function ($url,$model,$key){
                    return Html::a('修改 |  ',$url);
                    },
                    'delete-question'=>function ($url,$model,$key){
                    return Html::a('删除   ',$url);
                    },
           
           ],
           ],
        ],
    ]); ?>
  </div>
  </div>
  
  <div class="box box-success">
                <div class="box-header with-border">
                  <p class="box-title">评价关系</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
  <p> <?= Html::a('导入评价关系', ['import-relation', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> </p>
    <?= GridView::widget([
        'dataProvider' => $relationData,
     //   'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            ['attribute'=>'beuser.real_name',
              'label'=>'被评价人姓名'
             ],
            'work_number',
            ['attribute'=>'beuser.real_name',
                'label'=>'评价人姓名'
            ],
            'do_work_number',
            ['attribute'=>'type','value'=>function ($model){
                return CommonUtil::getDescByValue('report', 'type', $model->type);
            }],  
           ['attribute'=>'created_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
           ['attribute'=>'updated_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->updated_at);
           }],
         
           ['class' => 'yii\grid\ActionColumn','header'=>'操作',
               'template'=>'{view-answer}{update-question}{delete-relation}',
               'buttons'=>[
                   'view-answer'=>function ($url,$model,$key){
                   return Html::a('查看 |  ',$url);
                    },
//                     'update-question'=>function ($url,$model,$key){
//                     return Html::a('修改 |  ',$url);
//                     },
                    'delete-relation'=>function ($url,$model,$key){
                    return Html::a('删除   ',$url);
                    },
           
           ],
           ],
        ],
    ]); ?>
  </div>
  </div>
</div>
