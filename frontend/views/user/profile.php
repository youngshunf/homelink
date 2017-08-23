<?php
use common\models\CommonUtil;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '个人信息-'.$model->real_name;
$user=yii::$app->user->identity;
?>
<style>
img{
	max-width:70px !important;
	margin-right: 20px
}
.mui-table-view:before{
	background:none;
}
</style>
  <ul class="mui-table-view">
				<li class="mui-table-view-cell">
				<form action="<?= Url::to(['upload-img'])?>" method="post" id="img-form" enctype="multipart/form-data">
				<input type="file" name="img" class="hide" id="imgfile">
				</form>
				<a class="mui-navigate-right" href="javascript:;" id="upload">
				<p ><span class="bold" style="line-height: 80px">头像 </span>
				<?php if(!empty($model->photo)){?>
						<img class=" mui-pull-right img-circle img-responsive head-img"  src="<?= yii::$app->params['photoUrl'].$model->path.'thumb/'.$model->photo?>">
					<?php } elseif (!empty($model->img_path)){?>
					<img class=" mui-pull-right img-circle  img-responsive head-img"  src="<?= $model->img_path?>" >
					<?php }else{?>
						<img class=" mui-pull-right img-circle img-responsive head-img"  src="<?= yii::$app->params['uploadUrl']?>/avatar/unknown.jpg">
						
						<?php }?>
				</p>	
				 </a>						
					
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">姓名</span>
					<span class="pull-right"><?= $model->real_name?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">工号</span>
					<span class="pull-right"><?= $model->work_number?></span>
					</p>
				</li>
				
			   <li class="mui-table-view-cell">
					<p><span class="bold">电话</span>
					<span class="pull-right"><?= $model->mobile?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">邮箱</span>
					<span class="pull-right"><?= $model->email?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">年龄</span>
					<span class="pull-right"><?= $model->age ?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">性别</span> <span class="pull-right"><?= CommonUtil::getDescByValue('user', 'sex', $model->sex)?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">省份</span> <span class="pull-right"><?= $model->province?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">城市</span> <span class="pull-right"><?= $model->city?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">城市公司</span>
					
					 <span class="pull-right">
					 <?php if(!empty($model->puser)){?>
					<?= $model->puser->company?>
					<?php }else{?>
					无
					<?php }?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">储备人才</span> <span class="pull-right"><?= $model->talent?></span></p>
				</li>

</ul>

<?php if($user->user_guid==$model->user_guid){?>

    <div class="bottom-btn">
     <a class="btn btn-success btn-block btn-lg" href="<?= Url::to(['update-profile'])?>">修改个人信息</a> 
    </div>
 <?php }?>
 
 <?php if($user->user_guid==$model->user_guid){?>
<script>
$('#upload').click(function(){
	$('#imgfile').click();
})

$('#imgfile').change(function(){
	$('#img-form').submit();
})


</script>
<?php }?>