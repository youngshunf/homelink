<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Vote */

$this->title = '新增投票';
$this->params['breadcrumbs'][] = ['label' => '投票管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
