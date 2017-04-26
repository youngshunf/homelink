<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewDistrict */

$this->title = '修改大区: ' . ' ' . $model->district_name;
$this->params['breadcrumbs'][] = ['label' => '优才面试', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->district_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="interview-district-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
