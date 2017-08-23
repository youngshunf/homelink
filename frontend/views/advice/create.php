<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Advice */

$this->title = '问题反馈';

?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
