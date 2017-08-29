<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = '编辑问卷' . ' - ' . $report->name;
$this->params['breadcrumbs'][] = ['label' => $report->name, 'url' => ['view', 'id' => $report->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="panel-white">
<?php $form = ActiveForm::begin(['id'=>'question-form']); ?>
	  <div class="row">

     <div  class="col-md-12">
     <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
     <?= $form->field($model, 'type')->dropDownList(['1'=>'上级','2'=>'下级','3'=>'同级','4'=>'自评']) ?>
     <input type="hidden" name="question" v-model="getListString">
     <?php ActiveForm::end(); ?>
		 <ul class="list-group">
		 <li class="list-item" v-for="(item,index) in list" style="margin-bottom: 20px;
    padding: 25px;
    background: #f7f7f7;
    border-radius: 5px;">
		 <h5>问题{{index+1 }}</h5>
		 <div class="form-group">
		 <label>标题</label>
		 <input class="form-control" v-model="item.name" placeholder="请输入标题">
		 </div>
		 
		 <div class="form-group">
		 <label>描述</label>
		 <textarea class="form-control" v-model="item.desc" placeholder="请输入描述"></textarea>
		 </div>
		 
		 <div class="form-group">
		 <label>是否必填</label>
		 <select v-model="item.required" class="form-control">
		 <option value="1">是</option>
		 <option value="0">否</option>
		 </select>
		 </div>
		 
		 <div class="form-group">
		 <label>类型</label>
		 <select v-model="item.type" class="form-control">
		 <option value="1">矩阵</option>
		 <option value="2">文本</option>
		 </select>
		 </div>
		 
		 <div class="form-group">
		 <label>指标</label>
		 <select v-model="item.target" class="form-control">
		 <option :value="n" v-for="n in targets">{{n.name}}</option>
		 </select>
		 </div>
		 		 
		 </li>
		 
		 <li class="list-item center">
		 <a class="btn btn-success" href="javascript:;"  @click="addItem()">添加问题</a>
		 </li>
		 </ul>   

    </div>

<div class="center">
  <button class="btn btn-primary " @click="submit()">提交</button>
</div>

 
     
  </div>
	

  

</div>

<script type="text/javascript">

 new Vue({
        el: '#main',
        
        data:function(){
            return {
            	list: JSON.parse('<?= $model->question ?>')  || [],
            	targets:JSON.parse('<?= $targets?>') || []
            	
            }
        },
        created:function(){
           var self=this;
          if(self.list.length<=0){
              
        	  self.addItem();
          }
        },
        methods:{
        	addItem:function(){
				var item={
					name:'',
					desc:'',
					required:'1',
					type:'1',
					target:{},
				};
				this.list.push(item);
				console.log(this.list);
        	},
        	addOption:function(item){
			  var v={
			    'label':''
			  };
			  item.option.push(v);
        	},
        	deleteOption:function(idx,item){
              item.option.splice(idx,1);
        	},
        	submit:function(){
               var nameDone=true;
               var targetDone=true;
               this.list.forEach(function(item,index){
					if(!item.name){
						nameDone=false;
					}
					if(!item.target.weight){
						targetDone=false;
					}
               });
               if(!nameDone || !targetDone){
					modalMsg('指标和权重必填!');
					return false;
               }
               $('#question-form').submit();
        	}
          },
          computed: {
            getListString:function(){
               return JSON.stringify(this.list);
            }
          },
          watch: {
          }
    })
 
 </script>
 
 <style>

</style>