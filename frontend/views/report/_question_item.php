<?php


use common\models\CommonUtil;
use yii\helpers\Url;

?>

    <ul class="mui-table-view">
					<li class="mui-table-view-cell">
						
						    <div class="row ">
                             <a href="<?= Url::to(['answer-question','id'=>$model->id])?>" class="mui-navigate-right">
                             <p class="bold b-padding" ><?=$model['name']?>    </p>   
                         
                            <div class="col-xs-12 p-padding">
                                
                                <p >测评类型:<span class="mui-badge mui-badge-success"><?= CommonUtil::getDescByValue('report', 'type', $model->type)?></span></p>  
                        
                            </div>
                            
                          </a>
						</div>
					</li>
		</ul>



