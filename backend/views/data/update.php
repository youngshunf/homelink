<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ResultsData */

$this->title = '修改数据: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '数据管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="panel-white">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
