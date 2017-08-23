<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchTask */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务结果';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
 <p>
        <?= Html::a('导出结果', ['export-result','id'=>$task_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'user.real_name',
            'user.nick',
            'work_number',
            'business_district',
            'score',
            'comment',
            ['attribute'=>'commentUser.nick',
            'label'=>'评论用户'
            ],
            ['attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i;s']
            ],

       /*      ['class' => 'yii\grid\ActionColumn','header'=>'操作',
            'template'=>'{view}{update}{delete}{task-result}',
            'buttons'=>[
                'task-result'=>function ($url,$model,$key){
                return Html::a('查看结果',$url,['class'=>'btn btn-success']);
            }
            ],
            ], */
        ],
    ]); ?>

</div>
