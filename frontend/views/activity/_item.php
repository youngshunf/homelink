<?php


use common\models\CommonUtil;
use yii\helpers\Url;

?>

    <ul class="cbp_tmtimeline">
					<li>
						<time class="cbp_tmtime" datetime=""><span><?= date("Y年m月",$model->start_time)?></span> <span><?= date("d",$model->start_time)?></span></time>
						<?php if($model->sign_start_time<=time()&&time()<=$model->sign_end_time){?>
						<div class="cbp_tmicon bg-green">正在进行</div>
						<?php }elseif ($model->sign_end_time<time()){?>
						<div class="cbp_tmicon  ">已结束</div>
						<?php }elseif ($model->sign_start_time>time()){?>
						<div class="cbp_tmicon  bg-red">尚未开始</div>
						<?php }?>
						<div class="cbp_tmlabel">
						    <div class="row list-item">
                             <a href="<?= Url::to(['view','id'=>$model->activity_id])?>">
                             <p class="bold"><?=$model['title']?>    </p>   
                          <div class="col-xs-4">
                            <img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
                            </div>
                            <div class="col-xs-8">
                                
                             
                                <p ><?=mb_substr(strip_tags($model['content']), 0,40,'utf-8')?>...</p>  
                        
                            </div>
                             <div class="col-xs-12">
                                  <p class="time">活动时间:<?= CommonUtil::fomatTime($model->start_time)?>- <?= date("H:i:s",$model->end_time)?></p>
                                                             
                             </div>
                          </a>
                            </div>
						</div>
					</li>
		</ul>



