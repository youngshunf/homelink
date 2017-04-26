<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Card */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
$this->registerJsFile('@web/js/swiper.min.js', ['position'=> View::POS_HEAD]);
$this->registerCssFile('@web/css/swiper.min.css');
$this->registerCssFile('@web/css/basic.swiper.css');
$this->registerCssFile('@web/css/Font-Awesome-3.2.1/css/font-awesome.min.css');
?>

<div class="card-form">

    <?php $form = ActiveForm::begin(['id'=>'card-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'district')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'shop')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'business_circle')->textInput(['maxlength' => 64])->label('负责商圈(多个商圈用,分割)') ?>

    <?= $form->field($model, 'building')->textInput(['maxlength' => 128])->label('负责楼盘(多个楼盘用,分割)')  ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'sign')->textarea(['rows' => 6]) ?>
    
  
  
    <label class="control-label"> 照片</label>
    <div class="img-container">
    <?php if($model->isNewRecord||empty($model->photo)){?>
            <div class="uploadify-button"> 
            </div>
    <?php }else{?>
        <img alt="头像" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
    <?php }?>
    </div>
   <input type="file" name="photo"  class="hide"  id="photo">
   <input type="hidden" name="imgData" id="imgData">
   <input type="hidden" name="imgLen" id="imgLen">
   <br>
  <input type="hidden" name="template" id="template">
  <label class="control-label"> 选择名片模板</label>
   <!-- Swiper -->
    <div class="swiper-container">
	<div class="swiper-prevButton"></div>
        <div class="swiper-wrapper">
            <div class="swiper-slide"  template="card" >                     
            <img class="img-responsive" src="../img/card-template.jpg"  />
            <i></i>
            </div>
            <div class="swiper-slide" template="black">
            	<img class="img-responsive" src="../img/black-template.jpg"   />
            	<i></i>
            </div>
            <div class="swiper-slide"  template="pink">
            <img class="img-responsive" src="../img/pink-template.jpg"    />	
            <i></i>
            </div>
            <div class="swiper-slide"  template="card">
            <img class="img-responsive" src="../img/color-t.png"   />
            <i></i>
            </div>
          
        </div>
	<div class="swiper-nextButton"></div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>


  

    <div class="form-group center">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
$('.img-container').click(function(){
  $('#photo').click();
});

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

var swiper = new Swiper('.swiper-container', {
    pagination: '.swiper-pagination',
    slidesPerView: 2,
    centeredSlides: true,
    paginationClickable: true,
    spaceBetween: 30
});

$('.swiper-slide').click(function(){
 var template=$(this).attr('template');
 $('#template').val(template);
 $('.swiper-slide').removeClass('checked');
 $(this).addClass('checked');
});

function check(){
	<?php if($model->isNewRecord){?>
    if($('#photo').val()==""||$('#photo').val()==null){
        modalMsg('请上传照片');
        return false;
    }
    <?php }?>
    if(!$('#template').val()){
        modalMsg('请选择模板');
        return false;
    }
    showWaiting('正在上传,请稍候...');
    return true;
}

</script>
