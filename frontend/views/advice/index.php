<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAdvice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '问题反馈';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],

            'user.real_name',
            'title',
            'content:ntext',
            ['attribute'=>'时间','value'=>function ($model){
                return CommonUtil::fomatTime($model->created_at);
            }],
   
                  [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view}{delete}',        			
				]
           	],
     
    ]); ?>

</div>
