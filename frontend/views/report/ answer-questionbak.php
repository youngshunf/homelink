<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = $question->name;
$this->registerJsFile('@web/js/angular.min.js', ['position'=> View::POS_HEAD]);
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
</style>
<div ng-app="myApp" >
<div class="panel-white" id="main"  ng-controller="myCtrl">
	 
 <ul class="mui-table-view" >
 <li class="mui-table-view-cell"  ng-repeat="item in list" ng-show="$index==cIndex">
  <p class="title">{{$index+1}} 、  {{item.name}} <span ng-if="item.required==1" class="red">*</span></p>
  <p class="desc"> {{item.desc}}</p>
   <div ng-repeat="v in item.relation">
    <p class="bold"> {{v.name}}</p>
    <div ng-if="item.type==1" class=" mui-radio mui-left" ng-repeat="n in v.option">
		<label>{{n.name}}</label>
		<input name="{{v.work_number+$parent.$parent.$index+$parent.$index}}" type="radio" ng-value="{{n.value}}" ng-model="v.value"  >
	</div>
	
	<textarea ng-if="item.type==2"  rows="3" cols="" placeholder="请输入" ng-model="v.value" ></textarea>
    
   </div>
  

 
  
 </li>
 </ul>
 <div class="panel-white">
 <a ng-show="cIndex!=0" class="btn btn-success pull-left"  ng-click="prev()">上一题</a>
  
  <a ng-show="(cIndex+1) < list.length"  class="btn btn-success pull-right"  ng-click="next()">下一题</a>
  
  <p class="clear"></p>
  
  <p ng-show="(cIndex+1) == list.length"  class="center" >
  <button class="btn btn-success btn-block btn-lg " ng-click="submit()" >提交</button>
  </p>
  
  </div>
  
  </div>
	</div>

<script type="text/javascript">
var app = angular.module("myApp", []).controller("myCtrl", function($scope) {
	$scope.relation=JSON.parse('<?= $reportRelation ?>')  || [];
	$scope.question=JSON.parse('<?= $question->question?>') || [];
	$scope.list=[];
	$scope.cIndex=0;

	for(var i in $scope.question){
	  	  $scope.question[i]['relation']=$scope.relation;
	  	  $scope.list.push($scope.question[i]);
	 }
	 
	$scope.prev=function(){
		this.cIndex -=1;
	}
	$scope.next=function(){
    	var val=true;
    	if($scope.list[$scope.cIndex].required==1){
    	$scope.list[$scope.cIndex].relation.forEach(function(item){
        	console.log(item,item.value);
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
	}
	$scope.submit=function(){
        console.log($scope.list);
        $('#question-form').submit();
 	}
});

// new Vue({
//     el: '#main',
//     data:function(){
//         return {
//        	relation: JSON.parse('<?= $reportRelation ?>')  || [],
//        	question:JSON.parse('<?= $question->question?>') || [],
//         	list:[],
//         	cIndex:0
//         }
//     },
//     created:function(){
//        var self=this;
//       for(var i in self.question){
//     	  self.question[i]['relation']=self.relation;
//     	  self.list.push(self.question[i]);
//       }
//       console.log(self.list);
//     },
//     methods:{
//     	prev:function(){
// 			this.cIndex -=1;
//     	},
//     	next:function(){
//         	var val=true;
//         	if(this.list[this.cIndex].required==1){
//         	this.list[this.cIndex].relation.forEach(function(item){
//             	console.log(item.value);
// 				if(!item.value){
// 					val=false;
// 				}
//         	})
//         	}
//         	if(!val){
// 				modalMsg('此题为必答题,请答完后再做下一题');
//         	}else{
//         		this.cIndex +=1;
//         	}
			
//     	},
//     	oninput:function(index,idx,value){
//         	console.log(index,idx);
//         	this.list[index].relation[idx].value=value;
//         	console.log(this.list);
			
//     	},
//     	submit:function(){
//            console.log(this.list);
//            $('#question-form').submit();
//     	}
//       },
//       computed: {
        
//       },
//       watch: {
//       }
// })
 
 </script>
 
