<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\Advice */

$this->title = $model->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p>【反馈内容】:<?= $model->content?></p>
     <p>【反馈时间】:<?= CommonUtil::fomatTime($model->created_at)?></p>


</div>
