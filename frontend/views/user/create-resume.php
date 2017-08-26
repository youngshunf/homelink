<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = '新增简历';

?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= $this->render('_resume_form', [
        'model' => $model,
        'schoolList'=>$schoolList
    ]) ?>

</div>
