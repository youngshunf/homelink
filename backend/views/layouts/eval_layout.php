<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
        $menuItems[] = ['label' => '评价内容', 'url' => ['/eval/index']  ];
           $menuItems[] = ['label' => '评价结果', 'url' => ['/eval/result']  ];   
               
            echo Nav::widget([
                'options' => ['class' => 'nav nav-pills nav-stacked '],
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
