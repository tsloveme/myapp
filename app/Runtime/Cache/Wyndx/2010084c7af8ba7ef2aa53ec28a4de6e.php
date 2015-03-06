<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>网络学习平台</title>
<meta http-equiv="Cache-Control" content="no-store">
	<script src="/Public/wyndx/jquery.js" type="text/javascript"></script>
	<script src="/Public/js/jquery-1.8.3.min.js" type="text/javascript"></script>
</head>
<body>
<script language="javascript">
$(function(){
	/*$.ajax({
		//async:false,   
		url:"http://192.168.0.100/index.php/wyndx/Index/getAnswerList_GET",
		type:"GET",
		dataType:"jsonp",
		data:{firstLetters:"abcdefghijklmnopqrstuvwxyz"},
		jsonp: "callback",
		jsonpCallback:"haha",
		success:function(data){
			alert(111);
		}
	});

*/

$.ajax({
		type:"GET",
		dataType:"jsonp",
		url:"http://192.168.0.100/index.php/wyndx/Index/getAnswerList_GET",
		jsonp:"callback",
		success:function(data){
			alert(data[1].examid);
		}
	
	});
})
</script>
</body></html>