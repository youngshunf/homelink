<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use backend\assets\AppAsset;
use common\models\CommonUtil;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$this->registerCssFile('@web/css/common.css');
$this->registerCssFile('@web/css/site.css');
$this->registerJsFile('@web/js/common.js');
$user=yii::$app->user->identity;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css">
       <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- 引入Vue -->
    <script src="//vuejs.org/js/vue.min.js"></script>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="//unpkg.com/iview/dist/styles/iview.css">
    <!-- 引入组件库 -->
    <script src="//unpkg.com/iview/dist/iview.min.js"></script>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-purple sidebar-mini" >
    <?php $this->beginBody() ?>
    <div class="wrapper">
      <header class="main-header">
        <!-- Logo -->
        <a href="<?= Url::to(['site/index'])?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>XVP</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>XVP管理后台</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
    

              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?= yii::getAlias('@avatar')?>/unknown.jpg " class="user-image" alt="User Image">
                  <span class="hidden-xs"><?= yii::$app->user->identity->username?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?= yii::getAlias('@avatar')?>/unknown.jpg " class="img-circle" alt="User Image">
                    <p>
                    <?= yii::$app->user->identity->username?>
                      <small><?= date("Y-m-d")?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                  <div class="col-xs-6 text-center">
                  <?php if($user->role_id==99){?>
                      <a href="<?= Url::to(['user/index'])?>">用户管理</a>
                    <?php }?>
                    </div>
                    <div class="col-xs-6 text-center">
                      <a href="<?= Url::to(['user/change-password'])?>">修改密码</a>
                    </div>
                    
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
<!--                     <div class="pull-left"> -->
<!--                       <a href="#" class="btn btn-default btn-flat"></a> -->
<!--                     </div> -->
                    <div class="pull-right">
                       <?= Html::a('注销', ['site/logout'], [
                                    'class'=>'btn btn-default btn-flat',
                                    'data' => [
                                        'method' => 'post',
                                    ],
                                ]) ?>

                    </div>
                  </li>
                </ul>
              </li>
           
            </ul>
          </div>
        </nav>
      </header>
      
       <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?=yii::getAlias('@avatar')?>/unknown.jpg" class="img-circle" alt="头像">
            </div>
            <div class="pull-left info">
              <p><?= yii::$app->user->identity->username?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> <?= CommonUtil::getDescByValue('admin_user', 'role_id', $user->role_id)?></a>
            </div>
          </div>
      
    
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="<?php if(yii::$app->controller->id=='site'&&yii::$app->controller->action->id=='index') echo "active";?> treeview">
              <a href="<?= Url::to(['site/index'])?>">
                <i class="fa fa-home"></i> <span>首页</span>
              </a>          
            </li>
            <?php if($user->role_id==99 ||$user->role_id==98){?>
            <li class="<?php if(yii::$app->controller->id=='user') echo "active";?> treeview">
              <a href="#">
                <i class="fa fa-user"></i>
                <span>用户管理</span>
               <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if(yii::$app->controller->id=='user'&&yii::$app->controller->action->id=='index') echo "active";?>" ><a href="<?= Url::to(['user/index'])?>"><i class="fa fa-circle-o"></i> 已验证用户</a></li>
              <?php if($user->role_id==99){?>
               <li class="<?php if(yii::$app->controller->id=='user'&&yii::$app->controller->action->id=='normal') echo "active";?>" ><a href="<?= Url::to(['user/normal'])?>"><i class="fa fa-circle-o"></i> 未验证用户</a></li>
                <li class="<?php if(yii::$app->controller->id=='user'&&yii::$app->controller->action->id=='manager') echo "active";?>" ><a href="<?= Url::to(['user/manager'])?>"><i class="fa fa-circle-o"></i> 管理员</a></li>
                <?php }?>
                <li class="<?php if(yii::$app->controller->id=='user'&&yii::$app->controller->action->id=='auth-user') echo "active";?>" ><a href="<?= Url::to(['user/auth-user'])?>"><i class="fa fa-circle-o"></i> 验证名单</a></li>
                <li class="<?php if(yii::$app->controller->id=='user'&&yii::$app->controller->action->id=='group') echo "active";?>" ><a href="<?= Url::to(['user/group'])?>"><i class="fa fa-circle-o"></i> 用户分组</a></li>
             	<li class="<?php if(yii::$app->controller->id=='user'&&yii::$app->controller->action->id=='template-message') echo "active";?>" ><a href="<?= Url::to(['user/template-message'])?>"><i class="fa fa-circle-o"></i> 模板消息</a></li>
              </ul>
            </li>
         
            
            <li class="<?php if(yii::$app->controller->id=='activity') echo "active";?> treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>活动报名</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
               <ul class="treeview-menu">
                <li class="<?php if(yii::$app->controller->id=='activity'&&yii::$app->controller->action->id=='activity') echo "active";?>" ><a href="<?= Url::to(['activity/index'])?>"><i class="fa fa-circle-o"></i> 活动列表</a></li>
                
              </ul>
            </li>
            
            <li class="<?php if(yii::$app->controller->id=='report') echo "active";?> treeview">
              <a href="#">
                <i class="fa fa-reorder"></i>
                <span>XVP360</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
               <ul class="treeview-menu">
                <li class="<?php if(yii::$app->controller->id=='report'&&yii::$app->controller->action->id=='index') echo "active";?>" ><a href="<?= Url::to(['report/index'])?>"><i class="fa fa-circle-o"></i> 测评列表</a></li>
              </ul>
            </li>
            
              <li class="<?php if(yii::$app->controller->id=='task' || yii::$app->controller->id=='mvpgrow') echo "active";?> treeview">
              <a href="#">
                <i class="fa fa-money"></i>
                <span>成长路径</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li class="<?php if(yii::$app->controller->id=='task'&&yii::$app->controller->action->id=='index') echo "active";?>"><a href="<?= Url::to(['task/index'])?>"><i class="fa fa-circle-o"></i> 任务列表</a></li>
                <li class="<?php if(yii::$app->controller->id=='mvpgrow'&&yii::$app->controller->action->id=='index') echo "active";?>" ><a href="<?= Url::to(['mvpgrow/index'])?>"><i class="fa fa-circle-o"></i> 成长路径</a></li>
              </ul>
            </li>
            
           
           <?php } ?>
            <?php if($user->role_id==99){?>
             <li class="<?php if(yii::$app->controller->id=='interview-supervisor') echo "active";?> treeview">
              <a href="#">
                <i class="fa fa-money"></i>
                <span>HM面试</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li class="<?php if(yii::$app->controller->id=='interview'&&yii::$app->controller->action->id=='index') echo "active";?>"><a href="<?= Url::to(['interview/index'])?>"><i class="fa fa-circle-o"></i> HM大区</a></li>
                 <li class="<?php if(yii::$app->controller->id=='interview'&&yii::$app->controller->action->id=='appeal') echo "active";?>" ><a href="<?= Url::to(['interview/appeal'])?>"><i class="fa fa-circle-o"></i> 申诉处理</a></li>
             	<li class="<?php if(yii::$app->controller->id=='interview'&&yii::$app->controller->action->id=='interview-photo') echo "active";?>" ><a href="<?= Url::to(['interview/interview-photo'])?>"><i class="fa fa-circle-o"></i> 面试照片</a></li>
             	<li class="<?php if(yii::$app->controller->id=='interview'&&yii::$app->controller->action->id=='interview-address') echo "active";?>" ><a href="<?= Url::to(['interview/interview-address'])?>"><i class="fa fa-circle-o"></i> 面试地址</a></li>
             	<li class="<?php if(yii::$app->controller->id=='interview'&&yii::$app->controller->action->id=='interview-data') echo "active";?>" ><a href="<?= Url::to(['interview/interview-data'])?>"><i class="fa fa-circle-o"></i> 大数据</a></li>
              </ul>
            </li>
            <?php }?>
           <?php if($user->role_id==89 || $user->role_id==88){?>
           <li class="<?php if(yii::$app->controller->id=='interview-supervisor') echo "active";?> treeview">
              <a href="#">
                <i class="fa fa-money"></i>
                <span>HM面试</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li class="<?php if(yii::$app->controller->id=='interview-supervisor'&&yii::$app->controller->action->id=='interview-supervisor') echo "active";?>"><a href="<?= Url::to(['interview-supervisor/index'])?>"><i class="fa fa-circle-o"></i> HM面试</a></li>
                 <li class="<?php if(yii::$app->controller->id=='interview-supervisor'&&yii::$app->controller->action->id=='interview-address') echo "active";?>" ><a href="<?= Url::to(['interview-supervisor/interview-address'])?>"><i class="fa fa-circle-o"></i> 面试地点</a></li>
             	<li class="<?php if(yii::$app->controller->id=='user'&&yii::$app->controller->action->id=='change-password') echo "active";?>" ><a href="<?= Url::to(['user/change-password'])?>"><i class="fa fa-circle-o"></i> 修改密码</a></li>
              </ul>
            </li>
           <?php }?>
           
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
      

        <div class="content-wrapper">
        <section class="content-header">
    
         <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="clear"></div>
        </section>

        <!-- Main content -->
        <section class="content">
        <?= Alert::widget() ?>
        <div class="box box-success">

    <div class="box-header width-border">
        <div class="box-title" >
            <?= Html::encode($this->title) ?>
        </div>
    </div>
    <div class="box-body" id="main">
        <?= $content ?>
        </div>
        </div>
        </section>
        </div>
    </div>

    
        <div id="overlay">
            <div class="overlay-body">
            <p class="overlay-msg"></p>
            <i class="icon-spinner icon-spin icon-2x"></i>
            </div>
            
    </div>
    
       <!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               提示
            </h4>
         </div>
         <div class="modal-body">
            	提示内容
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
	</div><!-- /.modal -->
    

     <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>e油网</b>
        </div>
        
      </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
