<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = '发布活动';
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
