<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
    <!--      <?= Html::a('新增管理员', ['create'], ['class' => 'btn btn-success pull-right']) ?>-->
    </p>
    <div class="clear"></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],

            'username',
            'real_name',
            ['attribute'=>'role_id','value'=>function ($model){
            	$role_id=$model->role_id;
            	return CommonUtil::getDescByValue('admin_user', 'role_id', $role_id);
            }],
            ['attribute'=>'last_ip','value'=>function ($model){
            	$last_ip=$model->last_ip;
            	return empty($last_ip)?"":$last_ip;
            }],
            ['attribute'=>'last_time','value'=>function ($model){
            	$last_time=$model->last_time;
            	return empty($last_time)?"":date("Y-m-d H:i:s",$last_time);
            }],

      /*       [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view}{update-role}{reset-password}',
	             'buttons'=>[
					'view'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看详细'] );
					},
					'update-role'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改用户权限'] );
					},		
					'reset-password'=>function ($url,$model,$key){
						return  Html::a('<span class="glyphicon glyphicon-refresh"></span>', $url, ['title' => '重置密码', 'data-confirm'=>'是否重置密码？'] );
					},
				]
           	], */
        ],
    ]); ?>

</div>
		<script type="text/javascript">
	  	function search(){
			var keywords = $("#keyword").val();		
		
			location.href="<?php echo Yii::$app->urlManager->createUrl('user/manager');?>?keywords="+keywords;
		}
     	</script>
