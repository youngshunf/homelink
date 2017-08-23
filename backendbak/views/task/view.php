<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '任务管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除此项目吗?',
                'method' => 'post',
            ],
        ]) ?>
          <?= Html::a('查看结果', ['task-result', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="row">
    <div class="col-md-6">
    <img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
    </div>
    
  <div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'standard',
            'score',
            'count_exec',
              ['attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i;s']
            ],
            ['attribute'=>'updated_at',
            'format'=>['date','php:Y-m-d H:i;s']
            ],
        ],
    ]) ?>
    </div>
   </div>
    <h5>任务要求</h5>
    <?= $model->requirement?>
</div>
