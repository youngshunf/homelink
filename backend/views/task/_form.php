<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use wenyuan\ueditor\Ueditor;
use kartik\widgets\DateTimePicker;
use yii\helpers\ArrayHelper;
use common\models\TaskStep;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
?>

<div class="row">

    <?php $form = ActiveForm::begin(['id'=>'task-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'standard')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'score')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'group_id')->dropDownList(ArrayHelper::map($group, 'id', 'group_name'))->label('发布范围') ?>
    
    <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择任务开始时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>
       <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择任务结束时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
      ]); ?>
      
      <div class="col-md-12">
<div class="form-group" id="steps">
         <h3>任务流程</h3>
         <?php
         $steps=TaskStep::find()->andWhere(['task_id'=>$model->id])->orderBy('step asc')->all();
         if($model->isNewRecord || count($steps)<=0){?>
         <div class="form-group step">
         <p>第1步:</p>
         <label class="">标题</label>
         <input class="form-control" name="steptitle[]" placeholder="请输入这一步的标题" >
         <label class="">类型</label>
         <select class="form-control" name="steptype[]">
          <option value="0">淘汰</option>
           <option value="1">通知</option>
         </select>
         <label class="">学分</label>
         <input class="form-control" name="stepscore[]" placeholder="请输入这一步的学分" required>
         <label>描述</label>
         <textarea class="form-control" rows="3" cols="" name="stepcontent[]"  placeholder="请输入这一步的描述,如时间，地点，联系人，注意事项"></textarea>
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
         <label>描述</label>
         <textarea class="form-control" rows="3" cols="" name="stepcontent[]"  placeholder="请输入这一步的描述,如时间，地点，联系人，注意事项"><?= $v->content?></textarea>
         </div>
         
         <?php } }?>
</div>
<p><span class="red pull-right fa fa-plus" style="font-size:25px" id="add-step"></span></p>
</div>

    <h5>任务描述</h5>
          <?= Ueditor::widget(['id'=>'requirement',
                'model'=>$model,
                'attribute'=>'requirement',
                'ucontent'=>$model->requirement,
                ]);  ?>
       <div class="form-group">
    <label class="control-label"> 封面图片</label>
    <div class="img-container">
    <?php if($model->isNewRecord||empty($model->photo)){?>
            <div class="uploadify-button"> 
            </div>
    <?php }else{?>
        <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
    <?php }?>
    </div>
   <input type="file" name="photo"  class="hide"  id="photo">
   </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

$('.img-container').click(function(){
    $('#photo').click();
})

document.getElementById('photo').addEventListener('change', function () {
    var that = this;
    lrz(that.files[0], {
        width: 300
    })
        .then(function (rst) {
            var img        = new Image();            
            img.className='img-responsive';
            img.src = rst.base64;    
            img.onload = function () {
           	 $('.img-container').html(img);
            };                 
            return rst;
        });
});

function check(){
	<?php if($model->isNewRecord){?>
    if(!$('#photo').val()){
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
$maxStep=TaskStep::find()->andWhere(['task_id'=>$model->id])->max('step');
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
       <select class="form-control" name="steptype[]">\
        <option value="0">淘汰</option>\
         <option value="1">通知</option>\
       </select>\
       <label class="">学分</label>\
       <input class="form-control" name="stepscore[]" placeholder="请输入这一步的学分" required>\
   <label>描述</label>\
   <textarea class="form-control" rows="3" cols="" name="stepcontent[]"  placeholder="请输入这一步的描述,如时间，地点，联系人，注意事项"></textarea>\
   </div>';
  $('#steps').append(html);
})

</script>