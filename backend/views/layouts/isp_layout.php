<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
     
           $menuItems[] = ['label' => 'HM面试', 'url' => ['/interview-supervisor/index']  ];   
           $menuItems[] = ['label' => '面试地点', 'url' => ['/interview-supervisor/interview-address']];
           $menuItems[] = ['label' => '修改密码', 'url' => ['/interview-supervisor/change-password']];
                    
            echo Nav::widget([
                'options' => ['class' => 'nav nav-pills nav-stacked '],
                'items' => $menuItems,
            ]); 
     
		?>
		</div>
        </div>
        <div class="col-lg-10 col-md-10"> 
         <div class="panel-white">     
      <h3><?= $this->title?></h3>
             <br>
        <?= $content ?>
        </div>
        </div>
         </div>
<?php $this->endContent();?>
