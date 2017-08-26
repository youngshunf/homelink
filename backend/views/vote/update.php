<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vote */

$this->title = '投票修改: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '投票管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->vote_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
