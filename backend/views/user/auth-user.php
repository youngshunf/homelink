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
$user=yii::$app->user->identity;
?>
<div class="user-index">

   <?php if($user->role_id!=99){?>
    <form enctype="multipart/form-data" method="post" action="<?php echo Url::to('import-auth')?>" onsubmit="return check()">
		<div class="">					
			<input type="file" value="文件" name="importfrom" id="importfrom" >	
			<span class="red">*只需导入新增加和需要修改的用户</span>
			<br>
			<input type="submit" value="导入验证名单"  class="btn btn-success" >
		  <p id="errorImport"></p>
		</div>
   </form>
    <?php }?>
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
            ['attribute'=>'puser.company',
                'label'=>'城市公司'
            ],
            'up_work_number',
            ['class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'template'=>'{delete-authuser}',
                'buttons'=>[
                'delete-authuser'=>function ($url,$model,$key){
                    return  Html::a('删除 ', $url, ['title' => '删除', 'data-confirm'=>'您确定删除此用户?'] );
                },
             ]
            ],
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