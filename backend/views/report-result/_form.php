<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\ReportResult */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-result-form">

    <?php $form = ActiveForm::begin(['id'=>'result-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>


    <?= $form->field($model, 'work_number')->textInput(['maxlength' => 64]) ?>
	 <?= $form->field($model, 'report_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
     ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>


   <div class="form-group">
    <label class="control-label"> 测评报告(pdf):</label>
    <?php if(!$model->isNewRecord|| !empty($model->photo)){?>
        <a alt="测评报告" href="<?= yii::$app->params['fileUrl'].$model->path.$model->photo?>" >下载测评报告</a>
    <?php }?>
   <input type="file" name="file"  id="file"  >
   </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

function check(){
	<?php if($model->isNewRecord){?>
	if(!$('#file').val()){
     modalMsg('请上传测评报告');
     return false;
	}
	<?php }?>
	return true;
}
</script>
