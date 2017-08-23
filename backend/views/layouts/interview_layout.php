<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
     
           $menuItems[] = ['label' => 'HM面试', 'url' => ['/interview/index']  ];   
           $menuItems[] = ['label' => '申诉处理', 'url' => ['/interview/appeal']];   
           $menuItems[] = ['label' => '面试照片', 'url' => ['/interview/interview-photo']];
           $menuItems[] = ['label' => '面试地点', 'url' => ['/interview/interview-address']];
           $menuItems[] = ['label' => '大数据', 'url' => ['/interview/interview-data']];
//            $menuItems[] = ['label' => '验证名单', 'url' => ['/user/auth-user']];
                    
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
