<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchReport */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '测评列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增测评', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            'desc:ntext',
            'start_time:datetime',
            'end_time:datetime',
            'report_time:datetime',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','header'=>'操作',
                'template'=>'{view}{update}{delete}{top}',
                'buttons'=>[
                    'view'=>function ($url,$model,$key){
                    return Html::a('查看 | ',$url);
                },
                'update'=>function ($url,$model,$key){
                return Html::a('修改 | ',$url);
                },
                'delete'=>function ($url,$model,$key){
                return Html::a('删除 | ',$url);
                },
                
                ],
            ],
        ],
    ]); ?>

</div>
