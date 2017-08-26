<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Advice */

$this->title = 'Update Advice: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Advices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
