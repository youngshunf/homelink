<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InterviewDistrict */

$this->title = 'Create Interview District';
$this->params['breadcrumbs'][] = ['label' => 'Interview Districts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-district-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
