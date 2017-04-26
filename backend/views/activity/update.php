<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = '修改活动: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->activity_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
