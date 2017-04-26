<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Card */

$this->title = '修改名片: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '名片管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->card_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
