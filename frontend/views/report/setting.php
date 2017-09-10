<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchReport */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权重和指标设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h5>级别权重</h5>
	<?= GridView::widget([
        'dataProvider' => $levelData,
//         'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'puser.company',
            'up',
            'down',
            'same',
            'self',
            ['class' => 'yii\grid\ActionColumn','header'=>'操作',
                'template'=>'{update-level}',
                'buttons'=>[
                'update-level'=>function ($url,$model,$key){
                return Html::a('修改  ',$url);
                  },
                
                ],
            ],
        ],
    ]); ?>
    
    
    <h5>指标和权重</h5>
    <p>
        <?= Html::a('新增指标', ['create-target'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $targetData,
//         'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            'weight',
            'puser.company',
            ['attribute'=>'created_at','value'=>function ($model){
                return CommonUtil::fomatTime($model->created_at);
            }],

            ['class' => 'yii\grid\ActionColumn','header'=>'操作',
                'template'=>'{update-target}{delete-target}',
                'buttons'=>[
                  
                'update-target'=>function ($url,$model,$key){
                return Html::a('修改 | ',$url);
                },
                'delete-target'=>function ($url,$model,$key){
                return Html::a('删除  ',$url);
                },
                
                ],
            ],
        ],
    ]); ?>

</div>
