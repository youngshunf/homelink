<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\Advice */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '问题反馈', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
     
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此条数据吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user.real_name',
            'title',
            'content:ntext',
           ['attribute'=>'时间','value'=>CommonUtil::fomatTime($model->created_at)]
        ],
    ]) ?>

</div>
