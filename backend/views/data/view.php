<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\ResultsData */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '数据管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除这条数据?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'year_month',
            'work_number',
            'name',
            'big_district',
            'business_district',
            'shop',
            'total_score',
            'honor_score',
            'co_index',
            'teach_score',
            'results',
            'youmi',
            'rank',
            'remark',
           ['attribute'=>'导入时间','value'=>CommonUtil::fomatTime($model->created_at)]
        ],
    ]) ?>

</div>
