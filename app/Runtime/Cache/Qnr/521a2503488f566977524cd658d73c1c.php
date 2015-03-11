<?php if (!defined('THINK_PATH')) exit();?> <!doctype html>
<html>
<meta charset="utf-8" />
<head>
	<title>去哪儿比价系统</title>
<script lanuage="javascript" type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
<script lanuage="javascript" type="text/javascript">
$(function(){
	$("#updateProxy").click(function(){
		$(this).attr("disabled","disabled").val("正在更新。。。");
		$(this).next(".state").html('<img src="/Public/images/loading.gif" height="16" />');
		$.ajax({
			url:"/index.php/Qnr/Index/getIpFromWeb",
			type:"GET",
			success:function(data){
				$(this).next().html('<span style="color">更新成功，'+data.num+'个可用！</span>');
			}
		});
		
	})
})
</script>
</head>
<body>
<div>
	<div style="width:800px; margin:0 auto;">
		<table>
			<tr>
				<td width=25%><input id="updateCHR" type="button" value="同步-城市-酒店-房型" />
				<span class="state"></span>
				</td>
				<td width=25%></td>
				<td width=25%></td>
				<td width=25%>
				<input id="updateProxy" type="button" value="更新代理服务器" />
					<span class="state"></span>
				</td>
			</tr>
		</table>
	</div>
</div>
</body>
</html>