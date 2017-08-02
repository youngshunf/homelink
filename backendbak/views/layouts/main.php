<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\FlatlabAsset;
use frontend\widgets\Alert;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => '链家优才微信公众号管理后台',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems[]='';
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];               
            } elseif(Yii::$app->user->identity->role_id==99) {
				$menuItems[] = [
					'label' => '首页', 
					'url' => ['/site/index'],
				    'active'=>yii::$app->controller->id=='site'
				];
				$menuItems[] = [
					'label' => '用户管理',
					 'url' => ['/user/index'],
				    'active'=>yii::$app->controller->id=='user'
				];
				$menuItems[] = [
				    'label' => '名片管理',
				    'url' => ['/card/index'],
				    'active'=>yii::$app->controller->id=='card'
				];
				$menuItems[] = [
				    'label' => '数据管理',
				    'url' => ['/data/index'],
				    'active'=>yii::$app->controller->id=='data'
				];
				$menuItems[] = [
				    'label' => '评价管理',
				    'url' => ['/eval/index'],
				    'active'=>yii::$app->controller->id=='eval'
				];
// 				$menuItems[] = [
// 				    'label' => '投票管理',
// 				    'url' => ['/vote/index'],
// 				    'active'=>yii::$app->controller->id=='vote'
// 				];
				$menuItems[] = [
				'label' => '报名管理',
				'url' => ['/activity/index'],
				    'active'=>yii::$app->controller->id=='activity'
				];		
				$menuItems[] = [
				    'label' => '任务管理',
				    'url' => ['/task/index'],
				    'active'=>yii::$app->controller->id=='task'
				];
				$menuItems[] = [
				    'label' => 'MVP成长记录',
				    'url' => ['/mvpgrow/index'],
				    'active'=>yii::$app->controller->id=='mvpgrow'
				];
				$menuItems[] = [
				    'label' => '问题反馈',
				    'url' => ['/advice/index'],
				    'active'=>yii::$app->controller->id=='advice'
				];
				$menuItems[] = [
				    'label' => 'HM面试',
				    'url' => ['/interview/index'],
				    'active'=>yii::$app->controller->id=='interview'
				];
                $menuItems[] = [
                    'label' => '注销 (' . yii::$app->user->identity->username. ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
				
            }elseif (Yii::$app->user->identity->role_id==89 || Yii::$app->user->identity->role_id==88){
                $menuItems[] = [
                    'label' => 'HM面试',
                    'url' => ['/interview-supervisor/index'],
                    'active'=>yii::$app->controller->id=='interview-supervisor'
                ];
                $menuItems[] = [
                    'label' => '注销 (' . yii::$app->user->identity->username. ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
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
    <footer class="footer">
        <div class="container">
     
        <p class="pull-right">链家优才公众号管理后台</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
