<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title='新增模板消息';
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
      <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
    <?= $form->field($model, 'template_id')->textInput(['maxlength' => 128]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'group_id')->dropDownList(ArrayHelper::map($group, 'id', 'group_name')) ?>

       <div id="optArr-container" >
            <div id="optArr">
            <div class="form-group">
                <label class="label-control">模板数据（数据格式: key.DATA）</label>  
                <?php if($model->isNewRecord){?>              
             <input type="text" name="optArr[]" class="form-control">       
             <?php }else{?>         
             <?php foreach ($templateData as $v){?>
             <input type="text" name="optArr[]" class="form-control" value="<?= $v->key.'.'.$v->value?>">  
             <?php }?>
             <?php }?>                  
            </div>
            </div>
               <p class="pull-right"><a id="addOpt" href="javascript:;"><span class="glyphicon glyphicon-plus " style="color: red;font-size:26px"> </span></a></p>
                <p class="clear"></p>
     </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
$("#addOpt").click(function(){
    var innerHtml='<div class="form-group">\
              <input type="text" name="optArr[]" class="form-control"></div>';
    $("#optArr").append(innerHtml);
});
function check(){

	var opt=0;
	 $("input[name='optArr[]']").each(function(index,item){
		 if($(this).val()){
			 opt++;
		    }
	 });

	    if(opt==0){
		    modalMsg("请至少输入一项数据");
	        return false;
	    }

	 showWaiting("正在提交,请稍候...");
	 return true;
}
</script>