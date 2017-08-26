<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模板消息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
   <p><a class="btn btn-success" href="<?= Url::to(['create-template'])?>">新建模板消息</a></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',   
        ],
        'columns' => [
//             ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            ['attribute'=>'id',
              'label'=>'模板标识'
            ],
            'name',
    		'template_id',
    		'url',
    		'group_id',   
            'group.group_name',
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-template}{update-template}{delete-template}',
	             'buttons'=>[
					'view-template'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看详细'] );
					},
					'update-template'=>function ($url,$model,$key){				
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '编辑模板'] );					       
				   
				},
					'delete-template'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除用户', 'data-confirm'=>'是否确定删除该模板？'] );
					},
					
				]
           	],
        ],
    ]); ?>

</div>

