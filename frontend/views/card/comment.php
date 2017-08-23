<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\Card */

$this->title = '评论'.$card->name;
$this->registerCssFile('@web/raty/jquery.raty.css');
$this->registerJsFile('@web/raty/jquery.raty.js');
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

  <?php $form = ActiveForm::begin(['id'=>'comment-form','options' => ['onsubmit'=>'return check()']]); ?>
    <div class="form-group">
    <label class="label-control">评&nbsp;&nbsp;&nbsp;&nbsp;分 &nbsp;&nbsp;</label>
    <span id="raty-score" class="center"></span>
    <input type="hidden" name="score" id="score">
    </div>
    
    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
    <div class="form-group center">
        <button type="submit" class="btn btn-success">提交</button>
    </div>
   <?php ActiveForm::end(); ?>
</div>
<script>
$(document).ready(function(){
	 $.fn.raty.defaults.path = '../raty/images';
	 $("#raty-score").raty();
});

function check(){
	var score=$('#raty-score').raty('score');
	if(!score){
	    modalMsg('请评分');
	    return false;
	}
	$('#score').val(score);
	return true;
}

</script>