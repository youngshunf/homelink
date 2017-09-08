<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = $question->name;
$this->registerJsFile('@web/js/vue.min.js', ['position'=> View::POS_HEAD]);
$this->registerJsFile('@web/js/lodash.min.js', ['position'=> View::POS_HEAD]);
?>
<style>
.mui-radio{
	line-height: 26px;
    display: inline-block;
}
.mui-checkbox.mui-left label, .mui-radio.mui-left label {
    padding-right: 15px;
    padding-left: 35px;
}
.mui-checkbox.mui-left input[type=checkbox], .mui-radio.mui-left input[type=radio] {
    left: 0;
	top:0;
	margin:0
}
.title{
	font-size:18px;
	font-weight:bold
}
.mui-table-view-cell p{
	margin-bottom:10px
}
.desc{
	    margin-left: 20px;
    color: #666 !important;
}
.bold {
    font-weight: bold;
    font-size: 17px;
    margin: 10px;
}
</style>
<div class="panel-white" id="main">
	 
 <ul class="mui-table-view">
 <li class="mui-table-view-cell"  v-for="(item,index) in list" v-show="index==cIndex">
  <p class="title">{{index+1}} 、  {{item.name}} <span v-if="item.required==1" class="red">*</span></p>
  <p class="desc"> {{item.desc}}</p>
   <div v-for="(v,idx) in item.relation">
    <p class="bold"> {{v.name}}</p>
    <div v-if="item.type==1" class=" mui-radio mui-left" v-for="(n,i) in v.option">
		<label>{{n.name}}</label>
		<input :name="v.work_number+index+idx" type="radio" :value="n" v-model="v.value"  >
	</div>
	
	<textarea v-if="item.type==2"  rows="3" cols="" placeholder="请输入" v-model="v.value"  ></textarea>
    
   </div>
  

 
  
 </li>
 </ul>
 <div class="panel-white">
 <a v-show="cIndex!=0" class="btn btn-success pull-left"  @click="prev()">上一题</a>
  
  <a v-show="(cIndex+1) < list.length"  class="btn btn-success pull-right"  @click="next()">下一题</a>
  
  <p class="clear"></p>
  
  <p v-show="(cIndex+1) == list.length"  class="center" >
  <button class="btn btn-success btn-block btn-lg " @click="submit()" >提交</button>
  </p>
  
  </div>
  
  </div>
	

<script type="text/javascript">
new Vue({
    el: '#main',
    data:function(){
        return {
        	relation: JSON.parse('<?= $reportRelation ?>')  || [],
        	question:JSON.parse('<?= $question->question?>') || [],
        	list:[],
        	cIndex:0
        }
    },
    created:function(){
       var self=this;
      for(var i in self.question){
    	  self.question[i]['relation']= _.cloneDeep(self.relation);
    	  self.list.push(self.question[i]);
      }
    
    },
    methods:{
    	prev:function(){
			this.cIndex -=1;
    	},
    	next:function(){
        	var val=true;
        	if(this.list[this.cIndex].required==1){
        	this.list[this.cIndex].relation.forEach(function(item){
				if(!item.value){
					val=false;
				}
        	})
        	}
        	if(!val){
				modalMsg('此题为必答题,请答完后再做下一题');
        	}else{
        		this.cIndex +=1;
        	}
			
    	},
    	submit:function(){
           var self=this;
           var res=[];
           _.each(self.relation,function(item,index){
                item.answer=_.cloneDeep(self.question);
				_.each(self.list,function(n,idx){
					if(item.work_number==n.relation[index].work_number){
						item.answer[idx].value=n.relation[index].value;
					}
				})
           });
           showWaiting('正在提交,请稍候...');
           $.ajax({
			url:"<?= Url::to(['submit-answer'])?>",
			data:{
				answer:self.relation
			},
			method:'post',
			success:function(rs){
			
			},
			error:function(e){

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
 
