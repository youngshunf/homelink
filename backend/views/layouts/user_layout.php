<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
     
           $menuItems[] = ['label' => '已验证用户', 'url' => ['/user/index']  ];   
           $menuItems[] = ['label' => '未验证用户', 'url' => ['/user/normal']];   
         //  $menuItems[] = ['label' => '管理员', 'url' => ['/user/manager']];
           $menuItems[] = ['label' => '验证名单', 'url' => ['/user/auth-user']];
           $menuItems[] = ['label' => '用户分组', 'url' => ['/user/group']  ];
           $menuItems[] = ['label' => '模板消息', 'url' => ['/user/template-message']  ];
                    
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
   <!--   <div class="  input-group search-form ">
               <div class="input-group-btn">
                  <button type="button" class="btn btn-default 
                     dropdown-toggle" data-toggle="dropdown">
                    <span  id="user_type" option=""> 全部用户</span>
                     <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                     <li><a href="javascript:void(0);"  class="cate" cate_guid="0">全部用户</a></li>
                     <li><a href="javascript:void(0);"  class="cate" cate_guid="1">外部用户</a></li>
                     <li><a href="javascript:void(0);"  class="cate" cate_guid="2">内部用户</a></li>                
                  </ul>                 
               </div>
               <span class="input-group-addon">开始时间</span>
               <input type="date" name="start_time"  id="start_time" class="form-control" value="<?php echo date("Y-m-d",time()-3600*24*7);?>">
               <span class="input-group-addon">截止时间</span><input type="date"  class="form-control"  name="end_time"  id="end_time" value="<?php echo date("Y-m-d",time());?>">
                 <span type="button" class="input-group-addon btn btn-success" onclick="importExcel()">导出</span>
            </div>
            
         <div class="input-group">
        <input type="text" class="form-control" name="keywords"  id="keyword" value="" placeholder="请输入用名称或昵称">
        <span class="btn btn-success input-group-addon" onclick="search()">搜索</span>
      
        </div>    
        -->
             <br>
<script type="text/javascript">
            $(".cate").click(function(){
                	var that=$(this);
                    var text=that.html();
                    var cate_guid=that.attr('cate_guid');
                  	 $("#user_type").text(text);
                	 $("#user_type").attr("option",cate_guid);         
            });
              	          
          	function importExcel(){
        		var user_type = $("#user_type").attr("option");
        		var start_time = $("#start_time").val();
        		var end_time = $("#end_time").val();		
//         		var user_type = $('#user_type').text();	
        		location.href="<?php echo Yii::$app->urlManager->createUrl('user/import-excel');?>?user_type="+user_type+"&start_time="+start_time+"&end_time="+end_time;
        	}
    
     </script>     
        <?= $content ?>
        </div>
        </div>
         </div>
<?php $this->endContent();?>
