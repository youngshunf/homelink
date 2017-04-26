<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php   
        $id=$_GET['id'];
        $menuItems[] = ['label' => '推广优惠', 'url' => ['/finance/recommend','id'=>$id]  ];      
           $menuItems[] = ['label' => '28天推广返还', 'url' => ['/finance/sales','id'=>$id]];
           $menuItems[] = ['label' => '团队奖励', 'url' => ['/finance/balance','id'=>$id] ];         
           $menuItems[] = ['label' => '基础收入', 'url' => ['/finance/little-balance','id'=>$id]];
           //$menuItems[] = ['label' => '团队销售奖', 'url' => ['/finance/wheel','id'=>$id]];
           $menuItems[] = ['label' => '车奖房奖', 'url' => ['/finance/car-house','id'=>$id]];
           $menuItems[] = ['label' => '快速奖', 'url' => ['/finance/fast','id'=>$id]];
                      
            echo Nav::widget([
                'options' => ['class' => 'nav nav-pills nav-stacked'],
                'items' => $menuItems,
            ]); 
     
		?>
		</div>
        </div>
        <div class="col-lg-10 col-md-10">       
        <?= $content ?>
        </div>
         </div>
<?php $this->endContent();?>
