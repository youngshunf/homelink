<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\web\View;
use common\models\ActivityStep;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动进度';
$this->registerJsFile('@web/js/vue.min.js', ['position'=> View::POS_HEAD]);
$this->registerJsFile('@web/js/iview.min.js', ['position'=> View::POS_HEAD]);
$this->registerCssFile('@web/css/iview.css', ['position'=> View::POS_HEAD]);
$steps=ActivityStep::find()->andWhere(['activity_id'=>$model->activity_id])->orderBy('step asc')->all();
?>
<style>
.mui-table-view:before{
	background:none
}

</style>
<!-- <link rel="stylesheet" type="text/css" href="http://unpkg.com/iview/dist/styles/iview.css"> -->
<!--     <script type="text/javascript" src="http://vuejs.org/js/vue.min.js"></script> -->
<!--     <script type="text/javascript" src="http://unpkg.com/iview/dist/iview.min.js"></script> -->
<div id="steps" class="content">
    <Steps :current="<?= $model->current_step-1?>" direction="vertical">
    <?php foreach ($steps as $v){?>
       <?php if($v->step>$model->current_step){?>
        <Step title="待进行" content="<?= $v->content?>"></Step>
        <?php }elseif($v->step==$model->current_step){?>
        <Step title="<?= CommonUtil::getDescByValue('step', 'status', $model->current_status)?>" content="<?= $v->content?>"></Step>
        <?php }else{?>
         <Step title="<?= CommonUtil::getDescByValue('step', 'status', $v->status)?>" content="<?= $v->content?>"></Step>
        <?php }?>
     <?php }?>
    </Steps>

</div>    

<script type="text/javascript">
new Vue({
    el: '#steps',
    data:function(){
    },
    created:function(){
    },
    computed:{
    },
    watch:{
    },
    methods:{
   	
      },
})
</script>


