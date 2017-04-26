
//消息提示框
function modalMsg(msg,title){
	if(title==null){
		title="提示";
	}
	$("#myModal .modal-title").html(title);
	$("#myModal .modal-body").html(msg);
	  $('#myModal').modal('show');		
}

//显示等待
function showWaiting(msg){
	$('.overlay-msg').html(msg);
	$('#overlay').show();
}
//关闭等待
function closeWaiting(){
	$('#overlay').hide();
}
