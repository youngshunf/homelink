<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserOperation;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '未验证用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],

            'nick',        
             ['attribute'=>'性别','value'=>function ($model){
        
            	return CommonUtil::getDescByValue('user', 'sex', $model->sex);
            }],    
            'country',
            'province',
            'city', 
    
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view}{delete}',
	             'buttons'=>[
					'view'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看详细'] );
					},								
					'delete'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除用户', 'data-confirm'=>'是否确定删除该用户？'] );
					}
				]
           	],
        ],
    ]); ?>

</div>
		<script type="text/javascript">
	  	function search(){
			var keywords = $("#keyword").val();		
		
			location.href="<?php echo Yii::$app->urlManager->createUrl('user/normal');?>?keywords="+keywords;
		}
     	</script>
