<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户分组-'.$model->group_name;
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.list-view div{
	width:18%;
	display:inline-block;
}
</style>
<div class="user-index">
    <p><a class="btn btn-info" href="#add-user" id="add-btn">增加分组用户</a></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',   
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
    		'real_name',
    		'nick',   
    	 	['attribute'=>'性别','value'=>function ($model){   		
    		    return CommonUtil::getDescByValue('user', 'sex', $model->sex);
    		}], 
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
            	'template'=>'{delete-group-user}',
	             'buttons'=>[
					'delete-group-user'=>function ($url,$model,$key){
	                     return  Html::a('从当前分组移除用户', $url, ['title' => '查看详细','class'=>'btn btn-danger','data-confirm'=>'确定将用户从当前分组移除？'] );
					},
				],
           	],
        ],
    ]); ?>

</div>
    <div id="add-user" class="add-user hide">
    <h5><?= $model->group_name?>--添加用户</h5>
    <?php $form=ActiveForm::begin([
        'action'=>'add-group-user',
       'options'=>['onsubmit'=>'return check()']
    ])?>
    <input type="hidden" name="groupid" value="<?= $model->id?>">
        <?= ListView::widget([
        'dataProvider'=>$userData,
        'itemView'=>'_user_item',            
           'layout'=>"{items}\n{pager}"
        ])?>
        <div class="center">
        <?= Html::submitButton('确认提交',['class'=>'btn btn-success'])?>
        </div>
    <?php ActiveForm::end()?>
    </div>

<script>
$('#add-btn').click(function(){
    $('#add-user').removeClass('hide');
});

        
 function check(){
	   var users=0;
	   $("input[type=checkbox]:checked").each(function(){
		    users++;
	   });

	   if(users==0){
		    modalMsg("请选择用户再提交");
		    return false;
	   }

	   showWaiting("正在提交,请稍候...");
	   return true;
 }
</script>