<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = '新增指标';
$this->params['breadcrumbs'][] = ['label' => '权重设置', 'url' => ['setting']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-create">


    <?= $this->render('_target_form', [
        'model' => $model,
    ]) ?>

</div>
