<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = '新建任务';
$this->params['breadcrumbs'][] = ['label' => '任务管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
