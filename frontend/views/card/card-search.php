<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCard */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '找合作';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.gold{
	color:rgb(255,215,0);
}
</style>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php  echo $this->render('_search', ['model' => $cardSearch]); ?>
        <table class="table table-responsive table-striped  table-bordered">
        <thead>
        <tr>
         <td>姓名</td>
         <td>大区</td>
          <td>备注</td>
          <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($result)&& is_array($result)){?>
          <?php foreach ($result as $v){?>
          <tr>
          <td><?= $v['name']?></td>
          <td><?= $v['district']?></td>
           <td><?= $v['businessCircle'].' | '.$v['building']?></td>
           <td><a class="btn btn-info" href="http://www.3meima.com:8080/goMyCardByNo.do?empNo=<?=$v['work_number']?>">名片</a></td>
          </tr>
         <?php }}?> 
        </tbody>
        </table>
</div>
