<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchActivity */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('发布活动', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
 
            'title',
           ['attribute'=>'scope','value'=>function ($model){
               return CommonUtil::getDescByValue('activity', 'scope', $model->scope);
           }],
           ['attribute'=>'type','value'=>function ($model){
               return CommonUtil::getDescByValue('activity', 'type', $model->type);
           }],
            ['attribute'=>'start_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->start_time);
           }],
           ['attribute'=>'end_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->end_time);
           }],
   
            // 'province',
            // 'city',
            // 'address',
            // 'max_number',
            // 'sign_end_time',
            // 'shop',
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
                    'top'=>function ($url,$model,$key){
                    if($model->is_top==0){
                        return Html::a('置顶  ',$url);
                    }else{
                        return Html::a('取消置顶  ',$url);
                    }
                    
                    },
                    ],
           ],
        ],
    ]); ?>

</div>
