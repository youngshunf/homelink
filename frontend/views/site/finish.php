<?php
/* @var $this yii\web\View */

$this->title = '旅游直销会员管理系统';
?>
<div class="panel-white">

    <div class="jumbotron">
    <?php if($msg=='success'){?>
        <h3>支付成功</h3>
        <p class="lead">您已经完成支付,感谢您加入国民旅游环球俱乐部！</p>
    <?php }elseif ($msg=='false'){?>
       <h3>支付失败</h3>
        <p class="lead">支付失败！您可以登录后到订单管理中继续支付</p>
        <?php }elseif ($msg=='error'){?>
       <h3>支付出错</h3>
        <p class="lead">支付出错！请联系系统管理员解决</p>
    <?php }?>
        <p>
        <a href="<?php echo yii::$app->urlManager->createUrl("site/index");?>" class="btn btn-lg btn-success" >确定</a>
        </p>
    </div>
</div>
