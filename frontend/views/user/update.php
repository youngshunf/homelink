<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = '修改愿望: ' . ' ' . $model->wish_title;

?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
