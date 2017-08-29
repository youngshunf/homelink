<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = '修改级别权重: ';
$this->params['breadcrumbs'][] = ['label' => '权重设置', 'url' => ['setting']];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="report-update">


     <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'up')->textInput(['maxlength' => 255]) ?>
     <?= $form->field($model, 'down')->textInput(['maxlength' => 255]) ?>
      <?= $form->field($model, 'same')->textInput(['maxlength' => 255]) ?>
       <?= $form->field($model, 'self')->textInput(['maxlength' => 255]) ?>
       <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
     <?php ActiveForm::end(); ?>

</div>
