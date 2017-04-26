<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
            $menuItems[] = ['label' => '总人数', 'url' => ['/report/index'] ];
           $menuItems[] = ['label' => '一周新入会员', 'url' => ['/report/new-members']  ];      
           $menuItems[] = ['label' => '一周确认会员', 'url' => ['/report/confirm-members']];
           $menuItems[] = ['label' => '一周直推排行榜', 'url' => ['/report/drive'] ];      
           $menuItems[] = ['label' => '一周收入排行榜', 'url' => ['/report/income'] ];
         
                    
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
