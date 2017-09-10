<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = $model->name;
?>



    <ul class="mui-table-view">
					<li class="mui-table-view-cell">
						
						    <div class="row ">
                             <h5 class="bold b-padding" ><?=$model['name']?>    </h5>   
                             <div class="col-xs-12">
                                  <p class="green">开始时间:<?= CommonUtil::fomatTime($model->start_time)?></p>
                                  <p class="green">结束时间:<?= CommonUtil::fomatTime($model->end_time)?></p>                           
                             </div>
                             <div class="col-xs-12 p-padding">
                                <p ><?= mb_substr(strip_tags($model['desc']), 0,80,'utf-8')?>...</p>  
                            </div>
						</div>
					</li>
		</ul>
	
<div class="panel-white">	
    <h5 class="box-title">测评问卷</h5>
               
    <?= ListView::widget([
        'dataProvider' => $questionData,
          'itemView'=>'_question_item',            
           'layout'=>"{items}\n{pager}"
    ]); ?>
 </div>
