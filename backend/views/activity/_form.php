<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use wenyuan\ueditor\Ueditor;
use yii\web\View;
use common\models\ActivityStep;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
$user=yii::$app->user->identity;
?>
<style>
.step{
	padding: 30px;
    margin-bottom: 20px;
    background: #f7f7f7;
    border-radius: 5px;
}
</style>


    <?php $form = ActiveForm::begin(['id'=>'activity-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>

  
    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>

<div class="row">   
<div class="col-md-6">
    <?= $form->field($model, 'scope')->dropDownList(['0'=>'所有人','1'=>'MVP','5'=>'SVP','6'=>'DVP','2'=>'商圈经理','3'=>'总监'])?>

   <?= $form->field($model, 'max_number')->textInput() ?>

    <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>

      <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>
       <?= $form->field($model, 'sign_start_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>
       <?= $form->field($model, 'sign_end_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>
    
     
</div>
<div class="col-md-6">
   <?php if($user->id==9336){?>
      <?= $form->field($model, 'type')->dropDownList(['0'=>'普通活动','2'=>'外部活动','3'=>'HM面试']) ?>
    <?php }else{?>
      <?= $form->field($model, 'type')->dropDownList(['0'=>'普通活动','2'=>'外部活动']) ?>
    <?php }?>
    <div class="hide" id="shop" >
        <?= $form->field($model, 'shop')->textInput(['maxlength' => 1024])->label('竞聘店面(多个店面用英文,号分隔)') ?>
    </div>
      <div class="hide" id="outer" >
        <?= $form->field($model, 'outer_link')->textInput(['maxlength' => 1024])->label('外部活动连接') ?>
          <?= $form->field($model, 'is_card_done')->dropDownList(['0'=>'否','1'=>'是'])->label('是否需要创建名片')?>
    </div>
     
   
    <?= $form->field($model, 'province')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 258]) ?>
 

</div>


<div class="col-md-12">
<div class="form-group" id="steps">
         <h3>活动流程</h3>
         <?php
         $steps=ActivityStep::find()->andWhere(['activity_id'=>$model->activity_id])->orderBy('step asc')->all();
         if($model->isNewRecord || count($steps)<=0){?>
         <div class="form-group step">
         <p>第1步:</p>
         <label class="">标题</label>
         <input class="form-control" name="steptitle[]" placeholder="请输入这一步的标题" >
         <label class="">类型</label>
         <select class="form-control steptype" name="steptype[]">
          <option value="0">淘汰</option>
           <option value="1">通知</option>
         </select>
         <label class="">学分</label>
         <input class="form-control" name="stepscore[]" placeholder="请输入这一步的学分" required>
         <label>通知</label>
         <textarea class="form-control" rows="3" cols="" name="stepcontent[]"  placeholder="请输入这一步的描述,如时间，地点，联系人，注意事项"></textarea>
         <div class=" denydesc">
         <label>淘汰通知</label>
         <textarea class="form-control" rows="3" cols="" name="denydesc[]"  placeholder="请输入这一步的淘汰通知,如该环节为通知类型，可以不填"></textarea>
         </div>
         </div>
         <?php }else{
         foreach ($steps as $v){
             ?>
         <div class="form-group step">
         <p>第<?= $v->step?>步:</p>
         <label class="">标题</label>
         <input class="form-control" name="steptitle[]" placeholder="请输入这一步的标题" value="<?= $v->title?>" >
         <label class="">类型</label>
         <select class="form-control" name="steptype[]" value="<?= $v->type?>">
           <option value="0" <?= $v->type==0?'selected=selected':''?> >淘汰</option>
           <option value="1" <?= $v->type==1?'selected=selected':''?>>通知</option>
         </select>
         <label class="">学分</label>
         <input class="form-control" name="stepscore[]" placeholder="请输入这一步的学分" required value="<?= $v->score?>">
         <label>通知</label>
         <textarea class="form-control" rows="3" cols="" name="stepcontent[]"  placeholder="请输入这一步的描述,如时间，地点，联系人，注意事项"><?= $v->content?></textarea>
         <div class=" denydesc">
         <label>淘汰通知</label>
         <textarea class="form-control" rows="3" cols="" name="denydesc[]"  placeholder="请输入这一步的淘汰通知,如该环节为通知类型，可以不填"><?= $v->deny_desc?></textarea>
         </div>
         </div>
         <?php } }?>
</div>
<p><span class="red pull-right fa fa-plus" style="font-size:25px" id="add-step"></span></p>
</div>

<div class="col-md-12">
<div class="form-group">
         <h3>活动介绍</h3>
          <?= Ueditor::widget(['id'=>'activity-content',
                'model'=>$model,
                'attribute'=>'content',
                'ucontent'=>$model->content,
                ]);  ?>
        </div>
  </div>    
    
  <div class="col-md-6">      
      <div class="form-group">
    <label class="control-label"> 缩略图片(150*150)</label>
    <div class="img-container">
    <?php if($model->isNewRecord||empty($model->photo)){?>
            <div class="uploadify-button"> 
            </div>
    <?php }else{?>
        <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
    <?php }?>
    </div>
   <input type="file" name="photoThumb"  class="hide"  >
   </div>
</div>  

 <div class="col-md-6">      
      <div class="form-group">
    <label class="control-label"> 封面图片(720*400)</label>
    <div class="img-container">
    <?php if($model->isNewRecord||empty($model->photo)){?>
            <div class="uploadify-button"> 
            </div>
    <?php }else{?>
        <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
    <?php }?>
    </div>
   <input type="file" name="photo"  class="hide"  >
   </div>
</div>  
</div>   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<script type="text/javascript">
$('#activity-type').change(function(){
    if($(this).val()==1){
        $('#shop').removeClass('hide');
        $('#outer').addClass('hide');
        $('#youcai-interview').addClass('hide');
    }else if($(this).val()==2){
   	 $('#outer').removeClass('hide');
        $('#shop').addClass('hide');
        $('#youcai-interview').addClass('hide');
    }else if($(this).val()==3){
      	 $('#outer').addClass('hide');
         $('#shop').addClass('hide');
         $('#youcai-interview').removeClass('hide');
     }else{
    	$('#shop').addClass('hide');
    	$('#outer').addClass('hide');
    	$('#youcai-interview').addClass('hide');
    }
});

$('.img-container').click(function(){
	$(this).parent().find('input[type=file]').click();
});

$("input[type=file]").change( function () {
	console.log('.photo click');
    var that = $(this);
    lrz(that.get(0).files[0], {
        width: 300
    })
        .then(function (rst) {
            var img        = new Image();            
            img.className='img-responsive';
            img.src = rst.base64;    
            img.onload = function () {
            	that.parent().find('.img-container').html(img);
            };                 
            return rst;
        });
});

function check(){
	<?php if($model->isNewRecord){?>
	var photo=0;
	$('input[type=file]').each(function(){
		if(!$(this).val()){
			photo++;
		}
	});

	if(photo!=0){
		 modalMsg('请上传照片');
	        return false;
	}
	
    <?php }?>
  
    showWaiting('正在提交,请稍候...');
    return true;
}
<?php if($model->isNewRecord){?>
var step=1;
<?php }else{
$maxStep=ActivityStep::find()->andWhere(['activity_id'=>$model->activity_id])->max('step');
    ?>
var step =<?= $maxStep?>;

<?php }?>

$('#add-step').click(function(){
	step +=1;
  var html='<div class="form-group step">\
       <p>第'+step+'步:</p>\
       <label class="">标题</label>\
       <input class="form-control" name="steptitle[]" placeholder="请输入这一步的标题" required>\
       <label class="">类型</label>\
       <select class="form-control steptype" name="steptype[]">\
        <option value="0">淘汰</option>\
         <option value="1">通知</option>\
       </select>\
       <label class="">学分</label>\
       <input class="form-control" name="stepscore[]" placeholder="请输入这一步的学分" required>\
   <label>通知</label>\
   <textarea class="form-control" rows="3" cols="" name="stepcontent[]"  placeholder="请输入这一步的描述,如时间，地点，联系人，注意事项"></textarea>\
   <div class="denydesc">\
   <label>淘汰通知</label>\
   <textarea class="form-control" rows="3" cols="" name="denydesc[]"  placeholder="请输入这一步的淘汰通知,如该环节为通知类型，可以不填"></textarea>\
   </div></div>';
  $('#steps').append(html);
})

$(document).on('change','.steptype',function(){
   var type=$(this).val();
   if(type==0){

   }
})


</script>
