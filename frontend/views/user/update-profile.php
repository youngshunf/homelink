<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = '修改个人信息';

?>
<div class="panel-white">


    <?= $this->render('_user_form', [
        'model' => $model,
    ]) ?>

</div>
