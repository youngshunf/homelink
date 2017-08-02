<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '验证名单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

   
    <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-auth')?>" onsubmit="return check()">
		<div class="pull-right">					
			<input type="file" value="文件" name="importfrom" id="importfrom" >	
			<span class="red">*导入新数据会将原来的数据清空</span>
			<br>
			<input type="submit" value="导入验证名单"  class="btn btn-success" >
		  <p id="errorImport"></p>
		</div>

</form>
    
    <div class="clear"></div>
    <?php Pjax::begin(['id'=>'auth-user']);?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
            'work_number',
            'role_name',
            'big_district',
            'business_district',
            'shop',
            'up_work_number',

        ],
    ]); ?>
<?php Pjax::end();?>
</div>

<script>
$.pjax.reload({container:"#auth-user"});
	//检查导入数据
	function check(){
		var importfrom = $("#importfrom").val();
		if(importfrom==""){
			$("#errorImport").html("<font color='red'>请选择导入的文件</font>");
			return false;
		}else{
			showWaiting('正在上传,请稍候...');
			return true;
		}
	}
</script>