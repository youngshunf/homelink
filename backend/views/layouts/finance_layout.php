<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
           $menuItems[] = ['label' => '电子币管理', 'url' => ['/finance/index']  ];      
           $menuItems[] = ['label' => '积分管理', 'url' => ['/finance/points']];
           $menuItems[] = ['label' => '旅游币管理', 'url' => ['/finance/coin'] ];      
           $menuItems[] = ['label' => '一周消费情况', 'url' => ['/finance/total'] ];
           $menuItems[] = ['label' => '免月费人数及金额', 'url' => ['/finance/free'] ];
           $menuItems[] = ['label' => '奖励统计', 'url' => ['/finance/reward'] ];
           $menuItems[] = ['label' => '现金明细', 'url' => ['/finance/moneydetail'] ];
           $menuItems[] = ['label' => '提款明细', 'url' => ['/finance/withdraw-detail'] ];
           $menuItems[] = ['label' => '积分明细', 'url' => ['/finance/pointsdetail'] ];
           $menuItems[] = ['label' => '旅游币明细', 'url' => ['/finance/coindetail'] ];
           $menuItems[] = ['label' => '月报表', 'url' => ['/finance/monthdetail'] ];
           $menuItems[] = ['label' => '提款管理', 'url' => ['/finance/withdraw-manager'] ];
           $menuItems[] = ['label' => '注册积分发放', 'url' => ['/finance/send-points'] ];
           $menuItems[] = ['label' => '奖金发放', 'url' => ['/finance/send-award'] ];
           $menuItems[] = ['label' => '发放记录', 'url' => ['/finance/send-award-arch'] ];
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
