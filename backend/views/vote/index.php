<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\SearchVote */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '投票管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增投票', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],

            'title',
           ['attribute'=> 'start_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->start_time);
           }],
           ['attribute'=> 'end_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->end_time);
           }],
           ['attribute'=> 'created_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
       
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
