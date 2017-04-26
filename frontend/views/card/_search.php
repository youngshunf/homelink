<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchCard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['card-search'],
        'method' => 'get',
    ]); ?>

   
    <?= $form->field($model, 'name',['options'=>['class'=>'input-group']])->label('姓名',['class'=>'input-group-addon']) ?>
    <br>
    <?php  echo $form->field($model, 'district',['options'=>['class'=>'input-group']])->label('大区',['class'=>'input-group-addon']) ?>
    <br>
    <?php  echo $form->field($model, 'shop',['options'=>['class'=>'input-group']])->label('店面',['class'=>'input-group-addon']) ?>
    <br>
<?php  echo $form->field($model, 'business_circle',['options'=>['class'=>'input-group']])->label('商圈',['class'=>'input-group-addon']) ?>
    <br>
    <?php  echo $form->field($model, 'building',['options'=>['class'=>'input-group']])->label('楼盘',['class'=>'input-group-addon']) ?>
    <br>

    <div class="form-group ">
    <p class="pull-right">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </p>
    </div>

    <?php ActiveForm::end(); ?>

</div>
