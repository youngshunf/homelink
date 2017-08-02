<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InterviewRegister */

$this->title = 'Create Interview Register';
$this->params['breadcrumbs'][] = ['label' => 'Interview Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-register-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
