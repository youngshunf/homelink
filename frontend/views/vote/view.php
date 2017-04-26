<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Vote */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '投票管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->vote_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->vote_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此数据吗?',
                'method' => 'post',
            ],
        ]) ?>
        
        <?= Html::a('返回', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('导出投票结果', ['export-result', 'id' => $model->vote_id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <div class="row">
    <div class="col-md-6">
        <img src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>"  class="img-responsive"/>
    </div>
    
<div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [      
            'title',         
            ['attribute'=>'start_time','value'=>CommonUtil::fomatTime($model->start_time)],
            ['attribute'=>'end_time','value'=>CommonUtil::fomatTime($model->end_time)],
            'vote_number',
            ['attribute'=>'created_at','value'=>CommonUtil::fomatTime($model->created_at)],       
        ],
    ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    <h3>投票选项</h3>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            ['attribute'=> 'photo',
               'format' => 'html',
             'value'=>function ($model){
                if(!empty($model->photo)){
                    $imgPath=yii::getAlias('@photo')."/".$model->path.$model->photo;
                    return Html::img($imgPath,['width'=>'70px']);                    
                }else{
                    return '无图片';
                }
            }],
            'title',
            'content',       
            'vote_number',
           ['attribute'=> '创建时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],       
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{delete-item}',
	             'buttons'=>[											
					'delete-item'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除选项', 'data-confirm'=>'是否确定删除该选项？'] );
					}
				]
           	],
        ],
    ]); ?>
    
    <p><a class="btn btn-success pull-right" id="add">增加选项</a></p>
    
    <div id="add-item" class="hide">
        <div class="row">
        <div class="col-md-2"></div>
              <div class="col-md-8">
              <h3>增加选项</h3>
    <?= $this->render('_item_form', [
        'model' => $itemModel,
    ]) ?>
    </div>
          <div class="col-md-2"></div>
    </div>
    </div>
    </div>
</div>
</div>
<script type="text/javascript">
$('#add').click(function(){
   $(this).addClass('hide');
   $('#add-item').removeClass('hide');
   $('#add-item').show();
});

</script>
