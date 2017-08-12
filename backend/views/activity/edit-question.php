<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = '编辑问卷' . ' - ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->activity_id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="panel-white">
	  <div class="row">

     <div  class="col-md-12">
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
		 <option value="1">单选</option>
		 <option value="2">多选</option>
		 <option value="3">文本</option>
		 </select>
		 </div>
		 		 

        <div v-show="item.type=='1' || item.type=='2'">
        <div class="input-group" v-for="(v,idx) in item.option">
  		<span class="input-group-addon" >{{'选项' + (idx+1)}}</span>
          <input type="text" class="form-control" placeholder="请输入" v-model="v.label"  >
          <span class="input-group-addon" @click="deleteOption(idx,item)" ><span class="fa fa-minus red" ></span></span>
        </div>
        <p class="center">
         <button class="btn btn-warning" @click="addOption(item)"> + 添加选项</button>
        </p>
        </div>
        
        
        
		 
		 </li>
		 
		 <li class="list-item center">
		 <button class="btn btn-success"  @click="addItem()">添加问题</button>
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
            	
            	list: JSON.parse('<?= $question ?>')  || [],
            	
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
					type:'3',
					option:[{
						'label':''
						}],
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
				console.log(this.list);
				var content=this.list;
				 showWaiting('正在提交,请稍候...');
			        $.ajax({
			        	type:"post",
			        	url:"<?= Url::to(['submit-question'])?>",
			        	data:{
			            	activity_id:"<?= $model->activity_id?>",
			            	content:content
			        	},
			        	success:function(rs){
			            	console.log(rs);
			    			closeWaiting();
			    			if(rs=="success"){
			    				location.reload();
			    			}else{
			    				modalMsg('提交失败!');
			    			}
			    			
			        	},
			        	error:function(e){
			        		closeWaiting();
			        		modalMsg('提交失败:'+e.status);
			        	}

			        })
        	}
          },
          computed: {
            
          },
          watch: {
          }
    })
 
 </script>
 
 <style>

</style>