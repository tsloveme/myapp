<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>考试系统答案生成器</title>
<script  language="javascript" type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){

})
</script>
</head>

<body>
<h1><center>考试系统答案生成器</center></h1>
<div style="width:800px; margin:0 auto;">
<div><form action="/index.php/Wyndx/Index/uploadFile" method="post" enctype="multipart/form-data">
	<lable>选择已考试的html文件<lable>
	<input type="file" id="htmlfile" name="file" value="" />
	<input type="submit" value="处理答案" />
	<label style="color:red" id="State"></label>
	<span id="Loading" style="display:none"><img src="/Public/images/loading.gif" height="16" /></span>
	</div>
</form>

</div>


</body>
</html>