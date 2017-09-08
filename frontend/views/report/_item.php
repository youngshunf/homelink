<?php


use common\models\CommonUtil;
use yii\helpers\Url;

?>

    <ul class="mui-table-view">
					<li class="mui-table-view-cell">
						
						    <div class="row ">
                             <a href="<?= Url::to(['view','id'=>$model->id])?>">
                             <p class="bold b-padding" ><?=$model['name']?>    </p>   
                         
                            <div class="col-xs-12 p-padding">
                                
                                <p ><?= mb_substr(strip_tags($model['desc']), 0,80,'utf-8')?>...</p>  
                        
                            </div>
                             <div class="col-xs-12">
                                  <p class="green">开始时间:<?= CommonUtil::fomatTime($model->start_time)?></p>
                                  <p class="green">结束时间:<?= CommonUtil::fomatTime($model->end_time)?></p>                           
                             </div>
                          </a>
						</div>
					</li>
		</ul>



