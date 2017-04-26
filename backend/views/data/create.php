<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ResultsData */

$this->title = 'Create Results Data';
$this->params['breadcrumbs'][] = ['label' => 'Results Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="results-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
