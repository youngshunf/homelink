<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchResultsData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="results-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_guid') ?>

    <?= $form->field($model, 'work_number') ?>

    <?= $form->field($model, 'honor_score') ?>

    <?= $form->field($model, 'co_index') ?>

    <?php // echo $form->field($model, 'teach_score') ?>

    <?php // echo $form->field($model, 'results') ?>

    <?php // echo $form->field($model, 'youmi') ?>

    <?php // echo $form->field($model, 'rank') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
