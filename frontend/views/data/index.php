<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchResultsData */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '数据查询';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/Font-Awesome-3.2.1/css/font-awesome.min.css');
?>

<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>

    </p>



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
                     return  Html::a('查看数据', ['view','workNumber'=>$model->work_number], ['title' => '查看数据','class'=>'btn btn-info'] );
				},									
			]
			],	
        ],
        'tableOptions' => ['class' => 'table   table-responsive']
    ]); ?>

</div>
