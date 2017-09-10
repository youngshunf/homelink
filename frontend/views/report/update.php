<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = '修改测评: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '测评列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="report-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
