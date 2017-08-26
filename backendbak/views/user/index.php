<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '验证用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
     <p class="red"> *不选择时间，导出全部用户</p>
    <form action="<?= Url::to(['export-user'])?>" method="post" id="export-form" >
     <input type="hidden" name="_csrf" value="<?= yii::$app->request->getCsrfToken()?>">
     <div class="input-group">
         <span class="input-group-addon">开始时间</span>
         <input type="date" class="form-control" name="startTime" id="startTime">
         <span class="input-group-addon">结束时间</span>
         <input type="date" class="form-control" name="endTime" id="endTime">
         <span class="input-group-addon btn btn-success" id="export">导出</span>
      </div>
    </form>
    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',   
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
    		'real_name',
    		'nick',   
    	/* 	['attribute'=>'性别','value'=>function ($model){   		
    		    return CommonUtil::getDescByValue('user', 'sex', $model->sex);
    		}], */
    		'work_number',
    		/* 'province',
    		'city', */
            ['attribute'=>'用户角色','value'=>function ($model){
            	$role_id=$model->role_id;
            	return CommonUtil::getDescByValue('user', 'role_id', $role_id);
            }],
            'group.group_name',
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view}{delete}{sign-manager}',
	             'buttons'=>[
					'view'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看详细'] );
					},
					'sign-manager'=>function ($url,$model,$key){
					   if($model->is_sign_manager==0){
					       return  Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, ['title' => '设置为签到管理员', 'data-confirm'=>'是否将该用户设置为签到管理员,设置成功之后可扫码签到二维码进行签到？'] );					       
					   }
					   if($model->is_sign_manager==1){
					       return  Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, ['title' => '取消签到管理员', 'data-confirm'=>'是否将该用户的签到管理员权限取消,取消之后将不可进行签到?'] );
					   }
					   
				},
					'delete'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除用户', 'data-confirm'=>'是否确定删除该用户？'] );
					},
					
				]
           	],
        ],
    ]); ?>

</div>
		<script type="text/javascript">
		 $("#export").click(function(){
			    if($("#endTime").val()<$("#startTime").val()){
			        modalMsg("结束时间不能小于开始时间");
			    }
			    showWaiting("正在导出,请稍候...");
			    $("#export-form").submit();
			});
     	</script>
