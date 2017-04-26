<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2 col-md-2">
			<div class="leftmenu">
        <?php  
           $menuItems[] = ['label' => '会员费订单', 'url' => ['/order/member']  ];      
           $menuItems[] = ['label' => '产品订单', 'url' => ['/order/product']];      
                    
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
              <div class="input-group search-form">
               <div class="input-group-btn">
                  <button type="button" class="btn btn-default 
                     dropdown-toggle" data-toggle="dropdown">
                    <span  id="option" option=""> 搜索选项</span>
                     <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                     <li><a href="javascript:void(0);" id="order_num">订单编号</a></li>
                     <li><a  href="javascript:void(0);" id="user">用户</a></li>                                   
                  </ul>                 
               </div><!-- /btn-group -->
               <input type="text" class="form-control " name="keywords"  id="keyword" value="" placeholder="请输入用户名或订单编号搜索">
                 <span type="button" class="btn btn-success input-group-addon" onclick="search()">搜索</span>
            </div><!-- /input-group -->
         <script type="text/javascript">
    $("#order_num").click(function(){
      	 $("#option").text("订单编号");
    	 $("#option").attr("option",'order_num');   
    });      
        $("#user").click(function(){
          	 $("#option").text("用户");
        	 $("#option").attr("option",'user');   
      });      	     
      
  	function search(){
		var option = $("#option").attr("option");
		var keywords = $("#keyword").val();			
		location.href="<?php echo Yii::$app->urlManager->createUrl('order/search');?>?option="+option+"&keywords="+keywords;
	}
    
     </script>        
        <?= $content ?>
        </div>
         </div>
         </div>
<?php $this->endContent();?>
