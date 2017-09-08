<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\models\CommonUtil;
use frontend\assets\FlatlabAsset;
use common\models\Message;
use frontend\widgets\Alert;
use common\models\Wallet;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
   	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <?php if(isset($description)){?>
     <meta name="description" content="<?= $description?>" /> 
   <?php }else{?>
     <meta name="description" content="链家优才" />
   <?php }?>
   
	  <div id='wx_pic' style='margin:0 auto;display:none;'>
        <?php if(isset($weixinImg)){?>        
		<img src="<?= $weixinImg?>"/>		
        <?php }else{?>
       <img src="<?= yii::getAlias('@web')?>/img/youcai.jpg"/>	
        <?php }?>
         </div>
 
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>  
	 <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">      
        <div class="container">
        <nav class="mui-bar mui-bar-tab ">
			<a class="mui-tab-item btm-nav" href="#" data-id="1">活动</a>
			<a class="mui-tab-item btm-nav" href="#" data-id="2">任务</a>
			<a class="mui-tab-item btm-nav" href="#" data-id="3">XVP360</a>
			<a class="mui-tab-item btm-nav" href="#" data-id="4">个人中心</a>
		</nav>
     
         <?= Alert::widget() ?>
        <?= $content ?>
       
        </div>
    </div>
     <script type="text/javascript">
		$('.mui-bar').on('click','.btm-nav',function(){
			var id=$(this).data('id');
			switch (id){
			case 1:
				location.href="<?= Url::to(['activity/index'])?>";
				break;
			case 2:
				location.href="<?= Url::to(['task/index'])?>";
				break;
    		case 3:
    			location.href="<?= Url::to(['report/index'])?>";
    			break;
    		case 4:
    			location.href="<?= Url::to(['/user'])?>";
    			break;
			}
			
		});
     </script>  
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
        <p >Copyright  &copy;  <?= date('Y')?> 链家优才  </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
