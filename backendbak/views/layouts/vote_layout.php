<?php 
use yii\bootstrap\Nav;
use common\models\Category;
use common\models\CommonUtil;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
            $menuItems[] = ['label' => '产品管理', 'url' => ['/product/index'] ];
           $menuItems[] = ['label' => '旅游路线管理', 'url' => ['/product/line-view']  ];      
           if(yii::$app->user->identity->role_id==CommonUtil::SYS_MANAGER){
               $menuItems[] = ['label' => '审核产品', 'url' => ['/product/product-audit']  ];
           }
                              
            echo Nav::widget([
                'options' => ['class' => 'nav nav-pills nav-stacked'],
                'items' => $menuItems,
            ]); 
     
		?>
		</div>
        </div>
        <div class="col-lg-10 col-md-10"> 
          <div class="panel-white">   
          
                      
        <?= $content ?>
        </div>
        </div>
         </div>
<?php $this->endContent();?>
