<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\models\ActivityStep;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = '测评结果';
$this->params['breadcrumbs'][] = ['label' => '测评列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/mui.min.css');
?>
<div class="panel-white">


<div class="row">
    
   
   
     <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">测评结果</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"> 
                <p>
                
                
                <ul class="mui-table-view">
                <li class="mui-table-view-cell step">
                <?php if(!empty($model->douser)){?>
                <p>评价人姓名: <?= $model->douser->real_name?></p>
                <?php }?>
                <p>评价人工号: <?= $model->do_work_number?></p>
                <?php if(!empty($model->beuser)){?>
                <p>被评价人姓名: <?= $model->beuser->name?></p>
                <?php }?>
                <p>被评价人工号: <?= $model->work_number?></p>
                <p>评价组: <?= CommonUtil::getDescByValue('report', 'type', $model->type)?></p>
                </li>
                <?php $answer=$model->answer;
                if(!empty($answer)){
                    $answer=json_decode($answer,true);
                    foreach ($answer as $k=>$v){
                ?>
					<li class="mui-table-view-cell step">
					<h5><?= ($k+1).'、'.$v['name']?>  </h5>
					<div>
					<p style="padding-left: 20px"><?= $v['desc']?></p>
				
					<?php if($v['type']==1){?>
						<p style="padding-left: 20px"> 结果:<?= $v['value']['name']?></p>
						<p style="padding-left: 20px"> 分值:<?= $v['value']['value']?></p>
					<?php }else{?>
						<p style="padding-left: 20px"> 结果:<?= $v['value']?></p>
					<?php }?>
					
					<p style="padding-left: 20px">
					指标:<?= $v['target']['name']?></p>
					<p style="padding-left: 20px">
					指标权重:<?= $v['target']['weight']?></p>
					</div>
					</li>
					
					<?php } }?>
				</ul>
                
      </div>
      </div>
      
   
    </div>
    
  

        </div>

