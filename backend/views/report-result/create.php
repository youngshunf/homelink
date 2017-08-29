<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ReportResult */

$this->title = '上传测评报告';
$this->params['breadcrumbs'][] = ['label' => '测评报告列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-result-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
