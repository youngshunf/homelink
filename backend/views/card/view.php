<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Card */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '名片管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <p class="pull-right">
        <?= Html::a('修改', ['update', 'id' => $model->card_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->card_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此数据吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
    <div class="col-md-6">
        <img alt="头像" src="<?=yii::getAlias('@avatar').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
    </div>
    <div class="col-md-6">
        <p><b>姓名:</b><?=$model->name?></p>
        <p><b>电话:</b><?=$model->mobile?></p>
        <p><b>邮箱:</b><?=$model->email?></p>
        <p><b>大区:</b><?=$model->district?></p>
        <p><b>店面:</b><?=$model->shop?></p>
    </div>
    </div>

    <div class="row">
    <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [        
            'business_circle',
            'building',
            'address',
            'sign:ntext',         
            'template',
            ['attribute'=>'创建时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->created_at)],        
        ],
    ]) ?>
    </div>
    <div class="col-md-12">
    <h3>评论</h3>
    <?php Pjax::begin(['id'=>'comment'])?>
        <?= GridView::widget([
        'dataProvider' => $commentData,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            ['attribute'=>'评论人','value'=>function ($model){
                return empty($model->user)?'游客':$model->user->real_name;
            }], 
          //  'user.real_name',
            'content',
            'score',
            'ip',    
             ['attribute'=>'评论时间','value'=>function ($model){
                 return CommonUtil::fomatTime($model->created_at);
             }],              
                 [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{delete-comment}',
	             'buttons'=>[											
					'delete-comment'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除评论', 'data-confirm'=>'是否确定删除该条评论？'] );
					}
				]
           	],
        ],
    ]); ?>
    <?php Pjax::end()?>
    </div>
    </div>
</div>
<script>
$.pjax.reload({container:"#comment"});
   </script>