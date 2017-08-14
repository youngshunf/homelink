<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\ActivityRegister;
use common\models\InterviewRegister;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = $model->title;
$this->registerJsFile('@web/js/lodash.min.js');
$this->registerJsFile('@web/js/vue.min.js', ['position'=> View::POS_HEAD]);
?>
<style>

.wrap > .container {
  padding: 0;
}
img{
	display: inline-block;
	max-width:100%;
	height:auto;
}
.btn-group.open .dropdown-toggle{
	box-shadow:none;
	-webkit-box-shadow:none;
}
.btn-group,.dropdown-menu{
	width:100%
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
    <p>【地点】<?= $model->address?></p>
 </div>
 <div class="content">
   <h5>活动介绍</h5>
   <?= $model->content?>
 </div>  
 
  <div class="content">
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
  
   <?php $form = ActiveForm::begin(['id'=>'register-form','options' => ['class'=>'mui-input-group','onsubmit'=>'return checkAnswer()']]); ?>
 	<?= $form->field($registerModel, 'email',['labelOptions'=>['class'=>'hide']])->hiddenInput(['class'=>'hide'])?>
    <div class="mui-input-row">
		<label>姓名</label>
		<input type="text" class="mui-input-clear" name="name" placeholder="请输入姓名" value="<?= $user->real_name?>">
	</div>
    <div class="mui-input-row  " >
    	<label class="">工号</label>
    	<input class="mui-input-clear" type="text" name="work_number" placeholder="请输入工号" value="<?= $user->work_number?>"/>
    </div>
    <div class="mui-input-row  " >
    	<label class="">手机</label>
    	<input class="mui-input-clear" type="text" name="mobile" placeholder="请输入手机号" value="<?= $user->mobile?>"/>
    </div>
    <input type="hidden"  name="answer" id="answer">
     <div id="question">
       
    <ul class="list-group">
    <li class="list-item" v-for="(item,index) in list">
      <p class="bold">{{index+1}}、{{item.name}}</p>
     <p style="padding-left: 20px">{{item.desc}}</p>
    <ul class="mui-table-view" v-if="item.type==1">
     <li class="mui-table-view-cell mui-radio mui-left" v-for="n in item.option">
			<input :name="item.name" type="radio" v-model="item.value" :value="n.label">{{n.label}}
	</li>
    </ul>
    <ul class="mui-table-view" v-if="item.type==2">
     <li class="mui-table-view-cell mui-checkbox mui-left" v-for="n in item.option">
			<input :name="item.name" type="checkbox" @change="check(item)" v-model="n.value" :value="n.label">{{n.label}}
	</li>
    </ul>
  
    <textarea v-if="item.type==3" rows="3" v-model="item.value"  cols="" class="mui-input"></textarea>
    
    </li>
    </ul>
      </div>
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
   <button class="btn btn-success btn-block" type="submit">提交</button>
   
  <?php ActiveForm::end()?>
  </div>
  <?php }else{
  	?>
  	<form class="mui-input-group"  id="interview-form" action="<?= Url::to(['interview-register']) ?>" method="post" onsubmit="return check()"> 
  	<input type="hidden"  name="district_code" id="district_code">
  	<input type="hidden"  name="district_name" id="district_name">
  	<input type="hidden"  name="activity_id" value="<?= $model->activity_id?>">
  	<div class="mui-input-row">
		<label>姓名</label>
		<input type="text" class="mui-input-clear" name="name" placeholder="请输入姓名" value="<?= $user->real_name?>">
	</div>
    <div class="mui-input-row  " >
    	<label class="">工号</label>
    	<input class="mui-input-clear" type="text" name="work_number" placeholder="请输入工号" value="<?= $user->work_number?>"/>
    </div>
    <div class="mui-input-row  " >
    	<label class="">手机</label>
    	<input class="mui-input-clear" type="text" name="mobile" placeholder="请输入手机号" value="<?= $user->mobile?>"/>
    </div>
  	<div class="btn-group">
    <div class="mui-input-row dropdown-toggle " data-toggle="dropdown">
    	<label class="">大区</label>
    	<input class="mui-input-clear" type="text" placeholder="请输入大区名搜索" id="search"/>
    </div>
    <ul class="dropdown-menu" role="menu" id="district-list">
    <?php foreach ($districtList as $v){?>
    <li data-code="<?= $v->district_code?>" data-name="<?= $v->district_name?>"><a href="#"><?= $v->district_name?></a></li>
    <?php }?>
    </ul>
    </div>
     <div class="form-group center">
     <button class="btn btn-success btn-block " type="submit">提交</button>
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

 var answer=new Vue({
         el: '#question',
         data:function(){
             return {
             	list: [],
             }
         },
         created:function(){
			this.list=JSON.parse('<?= empty($model->question)?'[]':$model->question ?>')  || [];
			for(var i in this.list){
				this.list[i].value='';
			}
         },
         computed:{
        	 stringList:function(){
    				return JSON.stringify(this.list);
    		},
         },
         watch:{
			'list':function(){
				console.log(this.list);
			}
         },
         methods:{
        	
        	 check:function(item){
				item.value=_.map(_.filter(item.option,function(n){
						return n.value;
				}),'label').toString();
        	 }
           },
     })

 function checkAnswer(){
   console.log(answer.list);
   $('#answer').val(JSON.stringify(answer.list));
   return true;
 }

</script>


