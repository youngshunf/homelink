<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\ResultsData */

$this->title =empty($model)? "数据查询":$model->name;

?>
<div class="panel-white">
    <h3><?= Html::encode($this->title) ?></h3>

   
       <?php if(empty($model)){?>
            <p>暂无数据</p>
            <a class="btn btn-warning" href="<?= Url::to(['query-data'])?>">返回</a>
       <?php }else {?>
           <div class="form-group">
    <?= DatePicker::widget([
    'id'=>'yearMonth',
    'name'=>'yearMonth',
    'options' => ['placeholder' => '请选择月份'],
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyymm'
    ]
    ])?>
    </div>
     <p class="pull-right"><button class="btn btn-success"  id="query">查询</button></p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [    
           'year_month',
            'work_number',
            'name',
            'big_district',
            'business_district',
            'shop',
            'rank' ,// '个人业绩大区排名',
            'line_pr',
            'total_score' ,// '作战小组业绩大区排名',
            'results' ,// '状态',
            'teach_score',// '总学分',
            'co_index' ,// '任务卡学分',
            'honor_score',// '课程学分',
            'youmi' ,// '信誉积分换算学分',
            'remark',//'备注',
            
            
        ],
        'options' => ['class' => 'table  table-responsive detail-view']
    ]) ?>

    <?php }?>
</div>
<script>
$('#query').click(function(){
   
    var yearMonth=$('#yearMonth').val();
    if(!yearMonth){
        modalMsg('请选择月份');
        return;
    }
 <?php if(!empty($model)){?>
    location.href="<?= Url::to(['view','workNumber'=>$model->work_number])?>"+"&yearMonth="+yearMonth;
<?php }?>
    });


    </script>