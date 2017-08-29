<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ReportResult */

$this->title = '修改测评报告: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '测评报告', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="report-result-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
