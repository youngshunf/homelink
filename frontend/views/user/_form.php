<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);

?>

<div class="wish-form">

    <?php $form = ActiveForm::begin(['id'=>'wish-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>

    <?= $form->field($model, 'wish_title')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'type')->dropDownList(['0'=>'生活','1'=>'职业技能','2'=>'实物礼品']) ?>
    
    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-froup">
    <label class="label-control">截止时间</label>
    <input type="date" name="end_time"  class="form-control"  id="end_time" placeholder="请选择截止日期">
    </div>
    
    <?= $form->field($model, 'wish_from')->textarea(['rows'=>'4']) ?>

    <?= $form->field($model, 'meaning')->textarea(['rows'=>'4'])?>
   
    <?= $form->field($model, 'return')->textarea(['rows'=>'4']) ?>
    <div class="form-group">
<label class="control-label"> 照片</label>
    <div class="img-container">
    <?php if($model->isNewRecord ||empty($model->photo)){?>
            <div class="uploadify-button"> 
            </div>
    <?php }else{?>
        <img alt="头像" src="<?= yii::getAlias('@photo').'/'.$model->path.$model->photo?>" class="img-responsive">
    <?php }?>
    </div>
   <input type="file" name="photo"  class="hide"  id="photo">
   <input type="hidden" name="imgData" id="imgData">
   <input type="hidden" name="imgLen" id="imgLen">
</div>


    <div class="form-group center">
        <?= Html::submitButton( '提交' , ['class' => 'btn btn-success ' ]) ?>
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
        width: 1024
    })
        .then(function (rst) {
            $("#imgData").val(rst.base64); 
            $('#imgLen').val(rst.base64Len);            
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
	<?php if($model->isNewRecord||empty($model->photo)){?>
    if(!$('#photo').val()){
        modalMsg('请上传照片');
        return false;
    }
    <?php }?>

    if(!$("#end_time").val()){
   	 modalMsg('请选择愿望截止时间');
     return false;
    }
  
    showWaiting('正在提交,请稍候...');
    return true;
}

</script>   