<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;

?>

<a href="<?= Url::to(['view','id'=>$model['id']])?>">
    <div class="row wish-list">
    <h5 class="ellipsis"><?=$model['wish_title']?>    <span class="pull-right status">筹集中</span></h5>
    <div class="col-xs-4">
    <img alt="" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
    </div>
    <div class="col-xs-8">       
        <p class="ellipsis"><?=$model['wish_from']?></p>
    
        <p><i class="icon-adjust"></i> 性别:<?= CommonUtil::getDescByValue('user', 'sex', $model['user']['sex']) ?>  <i class="icon-bookmark"></i> 年龄:<?= $model['user']['age']?></p>        
        <p><i class="icon-map-marker"></i> <?= $model['user']['province']?> <?= $model['user']['city']?> <?= $model['user']['region']?></p>
        
       
    </div>
  
    <div class="col-xs-12">
    <div class="wish-info">
    <table>
       <tr>
       <td width="40%"><p ><label>目标金额:</label><i class="red">￥<?=$model['amount']?></i></p></td>
       <td width="30%"><p><label>已筹集:</label><i class="red">￥<?=$model['support_amount']?></i></p></td>
       <td width="30%">
       <p><label>支持人数:</label><span class="red"><?=$model['count_support']?></span></p>
       </td>
       </tr>
       <tr>
       
       <td colspan="2"> <p><label> 进度:</label><span class="red"><?=$model['support_rate']?>%</span></p></td>
       <td> <p class=""><a class="btn btn-danger  "  href="<?= Url::to(['view','id'=>$model['id']])?>">支持TA</a></p></td>
       </tr>
    </table>
    <p></p>
    
    

      
         </div>
        </div>
    </div>
</a>
