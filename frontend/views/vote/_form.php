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

    <?php $form = ActiveForm::begin(['id'=>'vote-form','options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
        <?php if($model->isNewRecord ){?>
            <?= $form->field($model, 'photo')->fileInput(['maxlength' => 256]) ?>
            <?php }?>
       <?= $form->field($model, 'start_time')->widget(DateTimePicker::classname(), [
					    'options' => ['placeholder' => '请选择时间'],
					    'pluginOptions' => [
					        'autoclose'=>true,
							'format' => 'yyyy-mm-dd H:i:s'
					    ]
					])?>
       <?= $form->field($model, 'end_time')->widget(DateTimePicker::classname(), [
					    'options' => ['placeholder' => '请选择时间'],
					    'pluginOptions' => [
					        'autoclose'=>true,
							'format' => 'yyyy-mm-dd H:i:s'
					    ]
					])?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'submit']) ?>
        <?= Html::a('返回','index',['class'=>'btn btn-info'])?>
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