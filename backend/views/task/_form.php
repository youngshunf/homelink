<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use wenyuan\ueditor\Ueditor;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
?>

<div class="panel-white">

    <?php $form = ActiveForm::begin(['id'=>'task-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'standard')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'score')->textInput() ?>
    <h5>任务要求</h5>
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

</script>