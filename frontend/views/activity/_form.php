<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use wenyuan\ueditor\Ueditor;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);

?>



    <?php $form = ActiveForm::begin(['id'=>'activity-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>

  
    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>

<div class="row">   
<div class="col-md-6">
    <?= $form->field($model, 'scope')->dropDownList(['0'=>'所有人','1'=>'MVP','2'=>'商圈经理','3'=>'总监'])?>

   <?= $form->field($model, 'max_number')->textInput() ?>

    <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd H:i:s'
        ]
    ]); ?>

      <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd H:i:s'
        ]
    ]); ?>
    
       <?= $form->field($model, 'sign_end_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd H:i:s'
        ]
    ]); ?>
</div>
<div class="col-md-6">

    <?= $form->field($model, 'type')->dropDownList(['0'=>'普通活动','1'=>'竞聘活动']) ?>
    <div class="hide" id="shop" >
        <?= $form->field($model, 'shop')->textInput(['maxlength' => 1024])->label('竞聘店面(多个店面用英文,号分隔)') ?>
    </div>
    <?= $form->field($model, 'province')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 258]) ?>

 

</div>
</div>
<div class="form-group">
         <h3>活动介绍</h3>
          <?= Ueditor::widget(['id'=>'activity-content',
                'model'=>$model,
                'attribute'=>'content',
                'ucontent'=>$model->content,
                ]);  ?>
        </div>
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
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<script type="text/javascript">
$('#activity-type').change(function(){
    if($(this).val()==1){
        $('#shop').removeClass('hide');
    }else{
        $('#shop').addClass('hide');
    }
});

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

</script>
