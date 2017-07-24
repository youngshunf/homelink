<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Advice */

$this->title = 'Create Advice';
$this->params['breadcrumbs'][] = ['label' => 'Advices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
