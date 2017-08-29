<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

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
            ['attribute'=>'desc','value'=>function ($model){
                return CommonUtil::cutHtml($model->desc,20);
            }],
            ['attribute'=>'start_time','value'=>function ($model){
                return CommonUtil::fomatTime($model->start_time);
            }],
            ['attribute'=>'end_time','value'=>function ($model){
                return CommonUtil::fomatTime($model->end_time);
            }],
            ['attribute'=>'report_time','value'=>function ($model){
                return CommonUtil::fomatTime($model->report_time);
            }],
            ['attribute'=>'created_at','value'=>function ($model){
                return CommonUtil::fomatTime($model->created_at);
            }],

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
                return Html::a('删除  ',$url);
                },
                
                ],
            ],
        ],
    ]); ?>

</div>
