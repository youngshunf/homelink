<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Vote */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vote-form">

    <?php $form = ActiveForm::begin(['id'=>'vote-item-form','options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'type')->dropDownList(['0'=>'单选','1'=>'多选'])->label('题型')?>
        <?php if($model->isNewRecord ){?>
            <?= $form->field($model, 'photo')->fileInput(['maxlength' => 256]) ?>
            <?php }?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'submit']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
/* $('#submit').click(function(){
  if($('#vote-form').submit()){
	    showdiv('正在提交,请稍候...');
  }
}); */

</script>