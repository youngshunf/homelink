<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\InterviewDistrict;
use common\models\InterviewPhoto;

/* @var $this yii\web\View */
/* @var $model common\models\InterviewDistrict */

$this->title = $model->district_name;
$this->params['breadcrumbs'][] = ['label' => '优才面试', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-district-view">


    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除这条记录吗?',
                'method' => 'post',
            ],
        ]) ?>
         <?= Html::a('导出报名数据', ['export', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'district_code',
            'district_name',
            'assistant_number',
            'assistant_name',
            'supervisor_number',
            'supervisor_name',
            'created_at',
        ],
    ]) ?>

<h5>报名情况</h5>
<p>
<button class="btn btn-success"  id="batch-pass-signup">报名资格批量通过</button>
       <button class="btn btn-danger"  id="batch-deny-signup">报名资格批量不通过</button>
 </p>
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'id',
            ],
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            
            'district_code',
            'district_name',
             'name',
             'mobile',
             'work_number',
            'year_month',
            ['attribute'=>'signup_result',
                'value'=>function ($model){
                return CommonUtil::getDescByValue('interview_register', 'signup_result', $model->signup_result);
            }
            ],
            ['attribute'=>'interview_result',
            'value'=>function ($model){
            return CommonUtil::getDescByValue('interview_register', 'interview_result', $model->interview_result);
            }
            ],
            ['attribute'=>'created_at',
                'label'=>'报名时间',
            'value'=>function ($model){
            return CommonUtil::fomatTime($model->created_at);
            }
            ],
            ['attribute'=>'is_send_signup',
            'label'=>'是否发送报名通知',
            'value'=>function ($model){
            return $model->is_send_signup==1?'是':'否';
            }
            ],
            ['attribute'=>'is_send_result',
            'label'=>'是否发送面试通知',
            'value'=>function ($model){
            return $model->is_send_result==1?'是':'否';
            }
            ],
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn',
               'header'=>'操作',
                'template'=>'{delete-register}',
                'buttons'=>[
                    'delete-register'=>function ($url,$model,$key){
                        return  Html::a('删除', $url, ['title' => '删除','data-confirm'=>"您确定要删除此报名记录吗?"] );
                    },
                ]
                
        ],
        ],
    ]); ?>
    
    <h5>总监签字照片</h5>
    <div class="row">
    <?php $photo=InterviewPhoto::findAll(['district_code'=>$model->district_code,'type'=>'1']);
    foreach ($photo as $v){
    ?>
    <div class="col-md-4">
    <a href="<?= yii::getAlias('@photo').'/'.$v->path.'standard/'.$v->photo ?>" target="_blank">
    <?=  Html::img(yii::getAlias('@photo').'/'.$v->path.'standard/'.$v->photo,['class'=>'img-responsive']);?>
    </a>
    </div>
    <?php }?>
    </div>
    
    <h5>面试现场照片</h5>
    <div class="row">
    <?php $photo=InterviewPhoto::findAll(['district_code'=>$model->district_code,'type'=>'2']);
    foreach ($photo as $v){
    ?>
    <div class="col-md-4">
    <a href="<?= yii::getAlias('@photo').'/'.$v->path.$v->photo ?>" target="_blank">
    <?=  Html::img(yii::getAlias('@photo').'/'.$v->path.'standard/'.$v->photo,['class'=>'img-responsive']);?>
    </a>
    </div>
    <?php }?>
    </div>
    
</div>

<script type="text/javascript">
$('#batch-deny-signup').click(function(){
    var keys= $("#grid").yiiGridView("getSelectedRows");
    console.log(keys);
    if(keys.length<=0){
      modalMsg('请至少选择一个');
      return false;
    }
    showWaiting('正在执行,请稍候...');
    $.ajax({
    	type:"post",
    	url:"<?= Url::to(['interview-supervisor/signup-deny'])?>",
    	data:{
        	keys:keys
    	},
    	success:function(rs){
        	console.log(rs);
			closeWaiting();
			location.reload();
    	},
    	error:function(e){
    		closeWaiting();
    		modalMsg('操作失败:'+e.status);
    	}

    })
})

$('#batch-pass-signup').click(function(){
    var keys= $("#grid").yiiGridView("getSelectedRows");
    console.log(keys);
    if(keys.length<=0){
        modalMsg('请至少选择一个');
        return false;
      }
      showWaiting('正在执行,请稍候...');
      $.ajax({
      	type:"post",
      	url:"<?= Url::to(['interview-supervisor/signup-pass'])?>",
      	data:{
          	keys:keys
      	},
      	success:function(rs){
          	console.log(rs);
  			closeWaiting();
  			location.reload();
      	},
      	error:function(e){
      		closeWaiting();
      		modalMsg('操作失败:'+e.status);
      	}

      })
})
</script>
