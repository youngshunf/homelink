<?php


use common\models\CommonUtil;
use yii\helpers\Url;

?>

    <div class="row list-item">
     <a href="<?= Url::to(['view','id'=>$model->vote_id])?>">
  <div class="col-xs-4">
    <img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
    </div>
    <div class="col-xs-8">
        
     <h5 class="ellipsis"><?=$model['title']?>    </h5>   
        <p ><?=mb_substr($model['content'], 0,100,'utf-8')?>...</p>    
        <p class="time">投票截止日期:<?= CommonUtil::fomatTime($model->end_time)?></p>     
    </div>
  </a>
    </div>

