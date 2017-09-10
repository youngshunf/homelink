<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模板下载';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
 <ul class="list-group">
  <li class="list-group-item">
  验证用户模板 <a class="pull-right" href="<?= yii::$app->params['uploadUrl'].'template/验证用户模板.xlsx'?>">下载</a>
  </li>
  <li class="list-group-item">
  用户分组模板 <a class="pull-right" href="<?= yii::$app->params['uploadUrl'].'template/用户分组模板.xlsx'?>">下载</a>
  </li>
  <li class="list-group-item">
  活动环节状态模板 <a class="pull-right" href="<?= yii::$app->params['uploadUrl'].'template/活动环节状态模板.xlsx'?>">下载</a>
  </li>
  <li class="list-group-item">
  成长记录模板 <a class="pull-right" href="<?= yii::$app->params['uploadUrl'].'template/成长记录模板.xls'?>">下载</a>
  </li>
  <li class="list-group-item">
  XVP360评价关系模板 <a class="pull-right" href="<?= yii::$app->params['uploadUrl'].'template/XVP360评价关系模板.xls'?>">下载</a>
  </li>
  <li class="list-group-item">
  测评报告批量上传模板 <a class="pull-right" href="<?= yii::$app->params['uploadUrl'].'template/测评报告批量上传模板.xlsx'?>">下载</a>
  </li>
 </ul>
    

</div>

