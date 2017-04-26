<?php
use common\models\CommonUtil;
/* @var $this yii\web\View */

$this->title = '链家优才微信公众号管理后台';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
    
            <div class="col-lg-4">
            <div class="panel-white">
                <h2>用户管理</h2>

                <p>已验证用户，未验证用户和验证名单的管理</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('user/index')?>">用户管理 &raquo;</a></p>
            </div>
            </div>
        <div class="col-lg-4">
        <div class="panel-white">
                <h2>名片管理</h2>

                <p>管理用户的名片，进行搜索，查看，删除操作</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('card/index')?>">名片管理 &raquo;</a></p>
            </div>
            </div>
       <div class="col-lg-4">
       <div class="panel-white">
                <h2>数据管理</h2>

                <p>管理用户查询的数据，进行查看和导入操作</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('data/index')?>">数据管理&raquo;</a></p>
            </div>
            </div>
          <div class="col-lg-4">
                <div class="panel-white">
                <h2>评价管理</h2>

                <p>管理商圈经理的评价</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('eval/index')?>">评价管理 &raquo;</a></p>
            </div>
            </div>
            
              <div class="col-lg-4">
                <div class="panel-white">
                <h2>投票管理</h2>

                <p>发布投票,查看结果</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('vote/index')?>">投票管理 &raquo;</a></p>
            </div>
            </div>
        
         <div class="col-lg-4">
                <div class="panel-white">
                <h2>报名管理</h2>

                <p>发布活动,查看报名结果</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('activity/index')?>">报名管理 &raquo;</a></p>
            </div>
            </div>
            
              <div class="col-lg-4">
                <div class="panel-white">
                <h2>任务管理</h2>

                <p>发布任务,查看任务结果</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('task/index')?>">任务管理 &raquo;</a></p>
            </div>
            </div>
            
              <div class="col-lg-4">
                <div class="panel-white">
                <h2>MVP成长记录</h2>

                <p>导入mvp成长数据,查看mvp成长记录</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('mvpgrow/index')?>">MVP成长记录 &raquo;</a></p>
            </div>
            </div>
            
             <div class="col-lg-4">
                <div class="panel-white">
                <h2>面试结果</h2>

                <p>导入和查看面试结果</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('interview-result/index')?>">面试结果 &raquo;</a></p>
            </div>
            </div>
            
              <div class="col-lg-4">
                <div class="panel-white">
                <h2>问题建议</h2>

                <p>管理用户的问题和建议</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('advice/index')?>">问题建议 &raquo;</a></p>
            </div>
            </div>
            
            <div class="col-lg-4">
                <div class="panel-white">
                <h2>HM面试</h2>
                <p>HM面试管理</p>
                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('interview/index')?>">优才面试 &raquo;</a></p>
            </div>
            </div>
            
        </div>

    </div>
</div>
