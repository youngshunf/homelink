<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use common\models\CommonUtil;
use yii\helpers\Json;
use yii\web\View;
use common\models\ResumePhoto;

$this->registerJsFile('@web/js/lodash.min.js');
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
/* @var $this yii\web\View */
/* @var $model common\models\Wish */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.img{
	display:block;
	border-radius:10px
}
</style>
<div class="wish-form">

    <?php $form = ActiveForm::begin(['id'=>'resume-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>

   <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 30]) ?>
     <?= $form->field($model, 'email')->textInput(['maxlength' => 30]) ?>
    <?= $form->field($model, 'sex')->dropDownList(['1'=>'男','2'=>'女']) ?>
    
      
    <?= $form->field($model, 'graduation_time')->widget(DatePicker::className(),[
        'options' => ['placeholder' => '请选择毕业时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm'
        ]
    ]); ?>
    <div class="btn-group" style="width: 100%">
    <div class="form-froup dropdown-toggle" data-toggle="dropdown">
    <label class="label-control">毕业院校</label>
    <input type="hidden"  name="schoolid" id="schoolid" value="<?= $model->schoolid?>">
    <input type="text" name="school"  class="form-control"  id="school" value="<?= $model->school?>" placeholder="请输入学校名称搜索">
    </div>
    <ul class="dropdown-menu" role="menu" id="school-list" style="width: 100%">
    <?php foreach ($schoolList as $v){?>
    <li data-id="<?= $v->id?>" data-name="<?= $v->school?>"><a href="#"><?= $v->school?></a></li>
    <?php }?>
    </ul>
    </div>
       <?= $form->field($model, 'top_edu')->dropDownList(['统招本科' => '统招本科','硕士以上'=>'硕士以上']) ?>
         <?= $form->field($model, 'rec_number')->textInput(['maxlength' => 20]) ?>
   
    <div class="form-group" >
    <label class="control-label"> 简历照片:(请上传简历照片)</label>
     <div>
    <?php $photos=ResumePhoto::findAll(['resumeid'=>$model->id]);
   
    foreach ($photos as $v){
    ?>
    <div class="img-container">
     <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$v->path.'thumb/'.$v->photo?>" class="img">
    </div>
    <?php }?>
    </div>
    <div id="photos">
    <div class="img-container">
            <div class="uploadify-button"> 
           
            </div>
            <input type="file" name="photo[]"  class="hide"  >
    </div>
    
    </div>
   </div>
   
    <div class="form-group center bottom-btn">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success btn-block btn-lg' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">

$(document).on('click','.uploadify-button',function(){
	$(this).parent().find('input[type=file]').click();
});

var index=0;
$(document).on('change','input[type=file]', function () {
	
    var that = $(this);
    console.log(that.get(0));
    lrz(that.get(0).files[0], {
        width: 300
    })
        .then(function (rst) {
            var img        = new Image();   
            img.className='img';         
            img.src = rst.base64; 
            index++;   
            img.onload = function () {
            	that.parent().find('.uploadify-button').css({'background':'none'});
            	that.parent().find('.uploadify-button').html(img);
            };   
            var html='<div class="img-container">\
            <div class="uploadify-button"></div>\
            <input type="file" name="photo[]"  class="hide"  >\
   			 </div>' ;
 			 $('#photos').append(html);             
            return rst;
        });
});
  function check(){
	  if( $(".has-error").length>0){		
			modalMsg("请填写正确再提交!");
		    return false;
		}	
	  

	    if(!$("#school").val()){
	        modalMsg('请选择毕业学校');
	        return false;
	    }

	    var e=0;
	    $("input[type=text]").each(function(){
	        if(!$(this).val()){
	            e++;
	        }
	    });
	    if(e>0){
	    	modalMsg("请填写完整再提交!");
	        return false;
	    }

	    showWaiting("正在提交,请稍后...");
	    return true;
  }

  var schoolList='<?= Json::encode($schoolList)?>';
  schoolList= JSON.parse(schoolList);
         	$('#school').on('input',function(){
         		var value=$(this).val();
         		console.log(value);
         		var list=_.filter(schoolList,function(item){
         			return item.school.indexOf(value)>-1;
         		})
         		console.log(list);
         		var html="";
         		if(list.length<1){
         			html='<li><a href="#">无搜索结果</a></li>';
         			
         		}else{
         			for(var i in list){
         				html +='<li data-id="'+list[i].id+'" data-name="'+list[i].school+'"><a href="#">'+list[i].school+'</a></li>';
         			}
         		}
         		$('#school-list').html(html);
         	})
         	$(document).on('click','#school-list li',function(){
         		var id=$(this).data('id');
         		var name=$(this).data('name');
         		$('#school').val(name);
         		$('#schoolid').val(id);
         		$('.btn-group').removeClass('open');
 });
</script>
