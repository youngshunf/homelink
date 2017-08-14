<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchWish */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wish-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_guid') ?>

    <?= $form->field($model, 'wish_title') ?>

    <?= $form->field($model, 'wish_from') ?>

    <?= $form->field($model, 'meaning') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'weixin') ?>

    <?php // echo $form->field($model, 'qq') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'return') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'count_support') ?>

    <?php // echo $form->field($model, 'count_love') ?>

    <?php // echo $form->field($model, 'count_comment') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'wish_guid') ?>

    <?php // echo $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'lnglat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
