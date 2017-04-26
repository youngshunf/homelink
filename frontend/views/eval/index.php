<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchQuestion */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评价';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
   

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'work_number',
            'user.name',
    
             ['class' => 'yii\grid\ActionColumn',
             'header' => '操作',
			 'template'=>'{view}',
             'buttons'=>[
				'view'=>function ($url,$model,$key){
                     return  Html::a('评价', ['view','workNumber'=>$model->work_number], ['title' => '评价','class'=>'btn btn-info'] );
				},									
			]
			],	
        ],
        'tableOptions' => ['class' => 'table   table-responsive']
    ]); ?>



</div>
