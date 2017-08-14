<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\models\ActivityStep;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/mui.min.css');
?>
<div class="panel-white">


    <p>
        <?= Html::a('修改', ['update', 'id' => $model->activity_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->activity_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此活动吗?',
                'method' => 'post',
            ],
        ]) ?>
        <a class="btn btn-info" href="<?= Url::to(['index'])?>">返回</a>
           <a class="btn btn-warning" href="#register-field">查看报名结果</a>
           <a class="btn btn-success" href="<?= Url::to(['export-register','activity_id'=>$model->activity_id])?>">导出报名结果</a>
       
    </p>
<div class="row">
    <div class="col-md-6">
        <img src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>"  class="img-responsive"/>
    </div>
    
<div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [      
            'title',         
            ['attribute'=>'start_time','value'=>CommonUtil::fomatTime($model->start_time)],
            ['attribute'=>'end_time','value'=>CommonUtil::fomatTime($model->end_time)],
          'max_number',
            ['attribute'=>'created_at','value'=>CommonUtil::fomatTime($model->created_at)],       
        ],
    ]) ?>
    </div>
</div>
  <div class="row">
          <div class="col-md-12">
<div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">活动信息</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
  
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [         
                   ['attribute'=>'scope','value'=> CommonUtil::getDescByValue('activity', 'scope', $model->scope)
           ],
           ['attribute'=>'type','value'=>
               CommonUtil::getDescByValue('activity', 'type', $model->type)
           ],
            ['attribute'=>'sign_start_time','value'=>
                CommonUtil::fomatTime($model->sign_start_time)
           ],
           ['attribute'=>'sign_end_time','value'=>
               CommonUtil::fomatTime($model->sign_end_time)
           ],
            'province',
            'city',
            'address',
            'max_number',
              ['attribute'=>'sign_end_time','value'=>
               CommonUtil::fomatTime($model->sign_end_time)
           ],
            'shop',
             ['attribute'=>'outer_link','label'=>'外部活动连接'  ],
            ['attribute'=>'created_at','value'=>
            CommonUtil::fomatTime($model->created_at)
            ],
            ['attribute'=>'updated_at','value'=>
            CommonUtil::fomatTime($model->updated_at)
            ],
      
        ],
    ]) ?>
  </div>
  </div>
    
    
  <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">活动介绍</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
    <?= $model->content?>
   </div>
   </div>
   
   
     <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">活动调研</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"> 
                <p>
                <a class="btn btn-success" href="<?= Url::to(['edit-question','activity_id'=>$model->activity_id ])?>">编辑问卷</a>
                <p class="red">注意:活动开始后再编辑问卷，可能导致已经报名的用户还是老问卷</p>
                
                </p>
                
                <ul class="list-group">
                <?php $question=$model->question;
                if(!empty($question)){
                  $question=json_decode($question,true);
                  foreach ($question as $k=> $v){
                ?>
					<li class="list-item step">
					<h5><?= ($k+1).'、'.$v['name']?></h5>
					<div>
					<p style="padding-left: 20px"><?= $v['desc']?></p>
					</div>
					<?php if($v['type']==1){?>
					<ul class="mui-table-view">
					<?php foreach ($v['option'] as $n){?>
						<li class="mui-table-view-cell mui-radio mui-left">
						<input name="radio" type="radio"><?= $n['label']?>
					    </li>
					
					<?php  }?>
					</ul>
					<?php }?>
					
					<?php if($v['type']==2){?>
					<ul class="mui-table-view">
					<?php foreach ($v['option'] as $n){?>
					
						<li class="mui-table-view-cell mui-checkbox mui-left">
						<input name="checkbox" type="checkbox"><?= $n['label']?>
					    </li>
					
					<?php  }?>
					</ul>
					<?php }?>
					
					<?php if($v['type']==3){?>
					<textarea rows="2" cols="" class="mui-input"></textarea>
					<?php }?>
					</li>
					
					<?php } }?>
				</ul>
                
      </div>
      </div>
      
   
    <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">活动进度</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"> 
    <div id="steps">
    
     <Steps :current="current">
     <div style="padding:15px">
      <?php $steps=ActivityStep::find()->andWhere(['activity_id'=>$model->activity_id])->orderBy('step asc')->all();
            $maxStep=count($steps);
      foreach ($steps as $v){
      ?>
        <Step title="<?= $v->title ?> - <?= CommonUtil::getDescByValue('step', 'status', $v->status) ?>" content="<?= $v->content?>"></Step>
       <?php }?>
      
     </Steps>
         </div>
        </div>
    </div>
    </div>
    
  

     <div class="box box-primary">
                <div class="box-header with-border">
                  <p class="box-title">报名结果</p>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"> 
    <p>
    <?php if($maxStep>0 & $maxStep != $model->current_step){?>
    <a class="btn btn-success " href="<?= Url::to(['next-step','activity_id'=>$model->activity_id])?>">进入下一环节</a>
    <?php }elseif($maxStep>0 & $maxStep == $model->current_step){?>
     <a class="btn btn-success " href="<?= Url::to(['next-step','activity_id'=>$model->activity_id])?>">结束活动</a>
    <?php }?>
    <?php if($model->current_type==0){?>
    <a class="btn btn-success " href="javascript:;" id="step-deny">当前环节批量淘汰</a>
    <a class="btn btn-success " href="javascript:;" id="step-pass">当前环节批量通过</a>
    <?php }?>
    <a class="btn btn-success " href="<?= Url::to(['export-register','activity_id'=>$model->activity_id])?>">导出报名结果</a></p>
  <p class="clear"></p>
    <?php Pjax::begin(['id'=>'register-field'])?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
     //   'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
                'checkboxOptions' => function($model, $key, $index, $column) {
                   if($model->current_status==99){
                       return ['disabled' => 'disabled'];
                   }
                }
            ],
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'work_number',
            'name',
            'mobile',
            'email',
            ['attribute'=>'created_at','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],    
           ['attribute'=>'是否签到','value'=>function ($model){
               return $model->is_sign==0?"否":"是";
           }],
           ['attribute'=>'签到时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->sign_time);
           }],
           ['attribute'=>'签到人','value'=>function ($model){
               return $model['manager']['real_name'];
           }],
           ['class' => 'yii\grid\ActionColumn','header'=>'操作',
               'template'=>'{view-answer}',
               'buttons'=>[
                   'view-answer'=>function ($url,$model,$key){
                   return Html::a('查看调研结果  ',$url);
           },
           
           ],
           ],
        ],
    ]); ?>
    <?php Pjax::end()?>
        </div>
        </div>
        </div>
</div>
</div>

<script>
    new Vue({
        el: '#main',
        data:function(){
            return {
            	current: <?= $model->current_step -1;?>
            }
        },
        methods: {
            
        }
    })
    $('#step-deny').click(function(){
        var keys= $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
        if(keys.length<=0){
          modalMsg('请至少选择一个');
          return false;
        }
        showWaiting('正在执行,请稍候...');
        $.ajax({
        	type:"post",
        	url:"<?= Url::to(['step-deny'])?>",
        	data:{
            	activity_id:"<?= $model->activity_id?>",
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
      $('#step-pass').click(function(){
        var keys= $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
        if(keys.length<=0){
          modalMsg('请至少选择一个');
          return false;
        }
        showWaiting('正在执行,请稍候...');
        $.ajax({
        	type:"post",
        	url:"<?= Url::to(['step-pass'])?>",
        	data:{
        		activity_id:"<?= $model->activity_id?>",
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
