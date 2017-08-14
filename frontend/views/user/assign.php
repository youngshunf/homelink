<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InterviewResult */

$this->title = '分配面试官';
?>
<h5><?= $this->title ?></h5>


    <?= $this->render('_assign_form', [
        'model' => $model,
        'user'=>$user
    ]) ?>

