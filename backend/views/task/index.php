<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchTask */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建任务', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            'standard',
            'count_exec',
            'score',
            ['attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i;s']
            ],

            ['class' => 'yii\grid\ActionColumn','header'=>'操作',
            'template'=>'{view}{update}{delete}{task-result}{export-result}',
            'buttons'=>[
                'task-result'=>function ($url,$model,$key){
                return Html::a('查看结果 | ',$url);
                 },
                 'export-result'=>function ($url,$model,$key){
                 return Html::a('导出结果',$url);
                 },
            ],
            ],
        ],
    ]); ?>

</div>
