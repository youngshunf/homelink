<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCard */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '找合作';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.gold{
	color:rgb(255,215,0);
}
</style>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php  echo $this->render('_search', ['model' => $cardSearch]); ?>

    <!-- <p class="red"> * 为MVP会员</p> -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
      /*       ['class' => 'yii\grid\SerialColumn','header'=>'序号'], */
            'name',       
            'big_district',
//              'score',
             /* 'business_circle',
             'building', */
        ['attribute'=>'备注',
           'format'=>'html',
           'value'=>function($model){
           if($model->role_name=='MVP'){
               return "<span class='gold'>MVP</span>";
           }else{
               return "";
           }
        }
        ],
               ['class' => 'yii\grid\ActionColumn',
             'header' => '操作',
			 'template'=>'{view}',
             'buttons'=>[
				'view'=>function ($url,$model,$key){
                     return  Html::a('名片', "http://www.3meima.com:8080/goMyCardByNo.do?empNo=".$model->work_number, ['title' => '查看','class'=>'btn btn-info'] );
				},									
			]
			],			
        ],
        'tableOptions'=>['class'=>'table table-responsive'],
    ]); ?>

</div>
