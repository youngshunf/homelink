<?php

use yii\helpers\Html;
use common\models\CommonUtil;

use common\models\Answer;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = ''.$question->title;
$this->registerCssFile('@web/css/mui.min.css');
$this->params['breadcrumbs'][] = ['label' => '评价管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $question->title, 'url' => ['view', 'id' => $question->qid]];
$this->params['breadcrumbs'][] = '查看结果';
?>
<style>
p{
	color:#000000;
}
</style>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <p><?= $question->content?></p>
  
 <h5><span class="red"><?=empty($evaluation->user)?'': $evaluation->user->name?></span>对<span class="red"><?= empty($evaluation->evalUser)?"":$evaluation->evalUser->name?></span>的评价结果</h5>
  
   <ul class="mui-table-view">
  <li class="mui-table-view-cell ">
   </li>
  <?php foreach ($option as $k=>$v){
      $answer=Answer::find()->select('answer')->andWhere(['work_number'=>$evaluation->work_number,'eval_work_number'=>$evaluation->eval_work_number,'qid'=>$v->qid,'oid'=>$v->oid])->one();
      ?>
   <li class="mui-table-view-cell ">
     <div >
        <label><?= ($k+1).'.【'.CommonUtil::getDescByValue('option', 'type', $v->type).'】'.$v->title?></label>
        <?php if(empty($answer)) continue;?>
        <?php if($v->type==3){?>    
       <p><?= $answer->answer?></p>        
        <?php }elseif ($v->type==0){
        $optArr=json_decode($v->content,true);      
            ?>   
       <p>选项<?=$answer->answer+1 ?>.<?=$optArr[intval($answer->answer)] ?></p>
        <?php }elseif ($v->type==1){
        $optArr=json_decode($v->content,true);
        $answers=Answer::find()->select('answer')->andWhere(['work_number'=>$evaluation->work_number,'eval_work_number'=>$evaluation->eval_work_number,'qid'=>$v->qid,'oid'=>$v->oid])->all();
           foreach ($answers as $a){ ?>   
          <p>  <p>选项<?=$a->answer+1 ?>.<?= $optArr[intval($a->answer)]?></p>
        <?php } }else{?>
           <p> <?= $answer->answer==1?'对':'错'?></p>
        <?php }?>
        </div>
   </li>  
  <?php }?>

  </ul>
  
  <p>
        <?= Html::a('返回', ['view', 'id' => $question->qid], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
