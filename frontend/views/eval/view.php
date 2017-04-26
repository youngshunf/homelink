<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = $model->title;
$this->registerCssFile('@web/css/mui.min.css');
?>
<style>
.wrap > .container {
  padding: 0;
}
p{
	color:#000;
}
</style>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= $model->content?>
    </p>

 
  <ul class="mui-table-view">
  <li class="mui-table-view-cell ">
   <h4 class="red">你将对<?= $user['name']?>进行调研</h4>
   <p>谢谢您愿意和我们分享您的心声</p>
   </li>
  <?php foreach ($option as $k=>$v){?>
   <li class="mui-table-view-cell ">
     <div id="<?=$v->oid?>" >
        <label><?=($k+1).'.【'.CommonUtil::getDescByValue('option', 'type', $v->type).'】'.$v->title?></label>
        <?php if($v->type==3){?>    
        <textarea rows="3" cols="" class="form-control" name="answer<?= $v->oid?>"  qid="<?= $v->qid?>" oid="<?= $v->oid?>"></textarea>
        
        <?php }elseif ($v->type==0){
        $optArr=json_decode($v->content,true);
            ?>   
        <ul class="mui-table-view-cell" >
            <?php foreach ($optArr as $key=>$o){?>
            <li class="mui-table-view-cell   mui-radio   mui-left">
						<input name="opt<?= $v->oid?>" type="radio" qid="<?= $v->qid?>" oid="<?= $v->oid?>"  value="<?= $key?>"> <?=$o?>
			</li>
            <?php }?>
        </ul>
        <?php }elseif ($v->type==1){
        $optArr=json_decode($v->content,true);
            ?>   
        <ul class="mui-table-view-cell" >
            <?php foreach ($optArr as $key=>$o){?>
            <li class="mui-table-view-cell  mui-checkbox  mui-left">
						<input name="opt<?= $v->oid?>" type="checkbox" qid="<?= $v->qid?>" oid="<?= $v->oid?>" value="<?= $key?>"> <?=$o?>
			</li>
            <?php }?>
        </ul>
        <?php }else{?>
         <ul class="mui-table-view-cell" >        
            <li class="mui-table-view-cell  mui-radio     mui-left">
						<input name="opt<?= $v->oid?>" type="radio" qid="<?= $v->qid?>" oid="<?= $v->oid?>" value="1"> 对
			</li>
           <li class="mui-table-view-cell  mui-radio   mui-left">
						<input name="opt<?= $v->oid?>" type="radio" qid="<?= $v->qid?>" oid="<?= $v->oid?>" value="0"> 错
			</li>
        </ul>
        <?php }?>
        </div>
   </li>  
  <?php }?>
  <li class="mui-table-view-cell" >
    <p><a class="btn btn-success btn-block" id="submit">提交</a></p>
  </li>
  </ul>

</div>
<script>
$('#submit').click(function(){
	submitAnswer();
});

$("input:radio").change(function(){
    var oid=$(this).attr('oid');
    $("#"+oid).removeClass('red');
}); 

$("input:checkbox").change(function(){
    var oid=$(this).attr('oid');
    $("#"+oid).removeClass('red');
});     

$("textarea").change(function(){
    if($(this).val()){
  	  var oid=$(this).attr('oid');
   	 $("#"+oid).removeClass('red');
    }
   
});           

function submitAnswer(){
    <?php foreach ($option as  $v){?>
        <?php if($v->type==3){?>
        var rVal=$("textarea[name=answer<?=$v->oid?>]").val();
        if(!rVal){
        	$("#<?=$v->oid?>").addClass('red');
        	modalMsg("请答完题再提交!");
        	return false;
        }
        <?php }else{?>
        var rVal=$("input[name=opt<?=$v->oid?>]:checked").val();
        if(!rVal){
        	$("#<?=$v->oid?>").addClass('red');
        	modalMsg("请答完题再提交!");
        	return false;
        }
        <?php }?>
    <?php }?>
	
        var data=[];
        var i=0;
        $("input:radio:checked").each(
        function(){
        data[i]={
        	qid:$(this).attr('qid'),
        	oid:$(this).attr('oid'),
        	answer:$(this).val()
        }
        i++;
        });
        $("input:checkbox:checked").each(
        		function(){
        		data[i]={
        			qid:$(this).attr('qid'),
        			oid:$(this).attr('oid'),
        			answer:$(this).val()
        		}
        		i++;
        });
        $("textarea").each(
        		function(){
        		data[i]={
        			qid:$(this).attr('qid'),
        			oid:$(this).attr('oid'),
        			answer:$(this).val()
        		}
        		i++;
        });
        showWaiting("正在提交,请稍候");
        $.ajax({
            url:"<?= Url::to(['eval-do'])?>",
            type:"post",
            data:{
                data:data,
                workNumber:"<?= $workNumber?>",
                qid:"<?= $model->qid?>"
                },
            success:function(rs){
            	alert(rs);
            	closeWaiting();
            	if(rs=="fail"){            	
            		modalMsg("提交失败,请重试");
            	}
            },
            error:function(e){
                closeWaiting();
               // modalMsg("提交失败,请重试");
               // alert(JSON.stringify(e));
            }
        })
}
</script>