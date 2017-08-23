<?php


use common\models\CommonUtil;
use yii\helpers\Url;

?>

    <ul class="mui-table-view">
					<li class="mui-table-view-cell">
						
						    <div class="row ">
                             <a href="<?= Url::to(['view','id'=>$model->activity_id])?>">
                             <p class="bold b-padding" ><?=$model['title']?>    </p>   
                          <div class="col-xs-12">
                            <img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
                            </div>
                            <div class="col-xs-12 p-padding">
                                
                                <p ><?=mb_substr(strip_tags($model['content']), 0,80,'utf-8')?>...</p>  
                        
                            </div>
                             <div class="col-xs-12">
                                  <p class="green">活动时间:<?= CommonUtil::fomatTime($model->start_time)?>- <?= date("H:i:s",$model->end_time)?></p>
                                                             
                             </div>
                          </a>
						</div>
					</li>
		</ul>



