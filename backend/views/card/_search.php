<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchCard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'card_id') ?>

    <?= $form->field($model, 'user_guid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'shop') ?>

    <?php // echo $form->field($model, 'business_circle') ?>

    <?php // echo $form->field($model, 'building') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'sign') ?>

    <?php // echo $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'template') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
