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

$this->title = '活动调研结果';
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/mui.min.css');
?>
<div class="panel-white">


<div class="row">
    
   
   
     <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">活动调研</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"> 
                <p>
                
                
                <ul class="mui-table-view">
                <li class="mui-table-view-cell step">
                <p>姓名: <?= $model->name?></p>
                <p>工号: <?= $model->work_number?></p>
                <p>手机: <?= $model->mobile?></p>
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
					<p style="padding-left: 20px">答案: <?= $v['value']?></p>
					</div>
					</li>
					
					<?php } }?>
				</ul>
                
      </div>
      </div>
      
   
    </div>
    
  

        </div>

