<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\ActivityRegister;
use common\models\InterviewRegister;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = $model->title;
$this->registerJsFile('@web/js/lodash.min.js');
?>
<style>

.content {
 
  padding: 8px;
  background: rgba(255, 255, 255, 1);
}
.wrap > .container {
  padding: 0;
}
img{
	display: inline-block;
	max-width:100%;
	height:auto;
}
</style>
  <div class="c_img">
            <img alt="<?= $model->title?>" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
            <div class="c_words">
            <?=$model['title']?>
            </div> 
    </div>
<div class="content">
  <h5><?=$model['title']?></h5>
  <p>【活动时间】<?= date("Y年m月d日 H:i",$model->start_time)?> - <?=date("Y年m月d日 H:i",$model->end_time)?></p>
    <p>【报名截止】<?= date("Y年m月d日 H:i",$model->sign_end_time)?></p>
    <p>【地点】<?= $model->address?></i></p>
   <h3>活动介绍</h3>
   <?= $model->content?>
   
     <h5>马上报名吧</h5>
   
     
   <?php if($model->sign_end_time<time()){?>
  <p class="bold"> 报名已截止,下次早点来哦!</p>
   <?php }elseif (!empty($model->sign_start_time)&&$model->sign_start_time > time()){?>
     <p class="bold"> 报名还未开始,晚点再来吧!</p>
   <?php }else{?>
     <?php if($model->type==2){?>
        <p class="center">
        <a class="btn btn-success"  href="<?= Url::to(['outer-register','id'=>$model->activity_id])?>">立即报名</a>
        </p>
     <?php }else{
     $countRegister=ActivityRegister::find()->andWhere(['activity_id'=>$model->activity_id])->count();
             if($countRegister>=$model->max_number){
         ?>
         <p class="bold"> 对不起，报名人数已满，下次早点来吧</p>
        <?php }else {?>
     
    <?php if($model->scope==0 || ($model->scope==1&&yii::$app->user->identity->role_id==1) || ($model->scope==2&&yii::$app->user->identity->role_id==2) || ($model->scope==3&&yii::$app->user->identity->role_id==3) ){?>
   <?php if($model->type!=3){?>
   <?php $form = ActiveForm::begin(['id'=>'register-form','options' => ""]); ?>
   <?= $form->field($registerModel, 'work_number')->textInput(['maxlength' => 8])?>
  <?= $form->field($registerModel, 'name')->textInput(['maxlength' => 10])?>
  <?= $form->field($registerModel, 'mobile')->textInput(['maxlength' => 11])?>
  <?= $form->field($registerModel, 'email')->textInput(['maxlength' => 32])?>
  <?= $form->field($registerModel, 'weixin')->textInput(['maxlength' => 32])?>
  <?php if($model->type==1){?>
    <div class="form-group">
        <label class="label-control">竞聘店面</label>
        <select name="signShop" class="form-control">
        <?php $shop=explode(',', $model->shop);
        foreach ($shop as $v){
        ?>
        <option value="<?=$v?>"><?=$v?></option>
        <?php }?>
        
        </select>
    </div>  
  <?php }?>
   <div class="form-group center">
   <button class="btn btn-success" type="submit">提交</button>
   </div>
  <?php ActiveForm::end()?>
  <?php }else{
//      $interviewModel=new InterviewRegister();
//      $interviewModel->work_number=$user->work_number;
//      $interviewModel->name=$user->name;
//      $interviewModel->mobile=$user->mobile;
//   	 $form = ActiveForm::begin(['id'=>'interview-form','action' => Url::to(['interview-register']),'options' => ['onsubmit'=>'return check()']]); 
//   	echo $form->field($interviewModel, 'work_number')->textInput(['maxlength' => 12]);
//   	echo $form->field($interviewModel, 'name')->textInput(['maxlength' => 10]);
//   	echo $form->field($interviewModel, 'mobile')->textInput(['maxlength' => 11]);
  	?>
  	<form  id="interview-form" action="<?= Url::to(['interview-register']) ?>" method="post" onsubmit="return check()"> 
  	<input type="hidden"  name="district_code" id="district_code">
  	<input type="hidden"  name="district_name" id="district_name">
  	<input type="hidden"  name="activity_id" value="<?= $model->activity_id?>">
  	<div class="form-group  " >
    	<label class="control-label">姓名</label>
    	<input class="form-control" type="text" name="name" placeholder="请输入姓名" value="<?= $user->real_name?>"/>
    </div>
    <div class="form-group  " >
    	<label class="control-label">工号</label>
    	<input class="form-control" type="text" name="work_number" placeholder="请输入工号" value="<?= $user->work_number?>"/>
    </div>
    <div class="form-group  " >
    	<label class="control-label">手机</label>
    	<input class="form-control" type="text" name="mobile" placeholder="请输入手机号" value="<?= $user->mobile?>"/>
    </div>
  	<div class="btn-group">
    <div class="form-group dropdown-toggle " data-toggle="dropdown">
    	<label class="control-label">大区</label>
    	<input class="form-control" type="text" placeholder="请输入大区名搜索" id="search"/>
    </div>
    <ul class="dropdown-menu" role="menu" id="district-list">
    <?php foreach ($districtList as $v){?>
    <li data-code="<?= $v->district_code?>" data-name="<?= $v->district_name?>"><a href="#"><?= $v->district_name?></a></li>
    <?php }?>
    </ul>
    </div>
     <div class="form-group center">
     <button class="btn btn-success btn-block" type="submit">提交</button>
     </div>
  	</form>
	<?php //ActiveForm::end()?>
	 
    <?php  } }else {?>
    <p>对不起,您没有报名权限!</p>
    <?php } } }?>
    
    <?php }?>
</div>
<script type="text/javascript">
function check(){
	if(!$('#district_code').val() || !$('#district_name').val()){
		 modalMsg('选择大区');
		 return false;
	}
	showWaiting("正在提交,请稍候....");
	return true;
}

  
    
      var districtList='<?= Json::encode($districtList)?>';
      districtList= JSON.parse(districtList);
             	$('#search').on('input',function(){
             		var value=$(this).val();
             		console.log(value);
             		var list=_.filter(districtList,function(item){
             			return item.district_name.indexOf(value)>-1;
             		})
             		console.log(list);
             		var html="";
             		if(list.length<1){
             			html='<li><a href="#">无搜索结果</a></li>';
             			
             		}else{
             			for(var i in list){
             				html +='<li data-code="'+list[i].district_code+'" data-name="'+list[i].district_name+'"><a href="#">'+list[i].district_name+'</a></li>';
             			}
             		}
             		$('#district-list').html(html);
             	})
             	$(document).on('click','#district-list li',function(){
             		var code=$(this).data('code');
             		var name=$(this).data('name');
             		$('#search').val(name);
             		$('#district_code').val(code);
             		$('#district_name').val(name);
             		$('.btn-group').removeClass('open');
     });

</script>


