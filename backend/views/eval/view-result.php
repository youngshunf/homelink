<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = '查看评价结果:'.$model->title;
$this->params['breadcrumbs'][] = ['label' => '评价管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->qid]];
$this->params['breadcrumbs'][] = '查看结果';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('返回', ['view', 'id' => $model->qid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('导出EXCEL', ['export-result', 'qid' => $model->qid], [
            'class' => 'btn btn-success',         
        ]) ?>

    </p>

      <?= GridView::widget([
        'dataProvider' => $data,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',   
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
    	
    		['attribute'=>'评价人','value'=>function ($model){
    		    if(!empty($model->user)){
    		   return $model->user->name;
    		    }
    		}],
    		['attribute'=>'评价工号','value'=>function ($model){
    		    if(!empty($model->user)){
    		        return $model->user->work_number;
    		    }
    		}],
    		['attribute'=>'被评价人','value'=>function ($model){
    		    if(!empty($model->evalUser)){
    		    return $model->evalUser->name;
    		    }
    		}],
    		['attribute'=>'被评价人工号','value'=>function ($model){
    		    if(!empty($model->evalUser)){
    		        return $model->evalUser->work_number;
    		    }
    		}],
    		['attribute'=>'评价标题','value'=>function ($model){
    		    return $model->question->title;
    		}],
    		['attribute'=>'评价时间','value'=>function ($opt){
    		    return CommonUtil::fomatTime($opt->created_at);
    		}],
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{eval-view}{eval-delete}',
	             'buttons'=>[
	                 'eval-view'=>function ($url,$model,$key){
	                 return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看结果'] );
	                 },
					'eval-delete'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除选项', 'data-confirm'=>'是否确定删除该选项？'] );
					},					
				]
           	],
        ],
    ]); ?>
 

</div>
