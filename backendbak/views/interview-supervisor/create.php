<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InterviewDistrict */

$this->title = '新增面试地点';
$this->params['breadcrumbs'][] = ['label' => '面试地点', 'url' => ['interview-address']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-district-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
