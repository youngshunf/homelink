<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InterviewResult */

$this->title = '新增面试结果';
$this->params['breadcrumbs'][] = ['label' => '面试结果', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
