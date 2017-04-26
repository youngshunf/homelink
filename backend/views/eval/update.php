<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = '修改评价: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '评价管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->qid]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
