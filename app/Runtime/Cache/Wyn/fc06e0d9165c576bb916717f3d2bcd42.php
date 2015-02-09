<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>酒店列表更新程序</title>
<script  language="javascript" type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
$("#updateProvince").click(function(){
	/*$.ajax(,
		dataType : 'json',
		//jsonp:"jsonpcallback",  
		success : function(data){
		alert(1);
			if (data == "-1"){
				$("#updateProvinceState").css("color","red").html("获取城市列表失败，请重试！");
			}
			else{
				if (data.cities==null){
					$("#updateProvinceState").css("color","red").html("城市列表为空");
					return;
				}
				$("#updateProvinceState").css("color","#333").html("正在更新省份列表");
				$("#updateProvinceLoading").show();
				//省份列表
				for ( var i = 0; i < data.cities.length; i++){
					$.post({
						url:"/index.php/Wyn/Index/proviceInsert",
						data:{
							provinceName : data.cities.provinceName,
							provinceNo : data.cities.provinceNo
						}
					});	 	
				}
			}	
		}
	});*/
});
$("#updateProvince").click(function(){
	$("#updateProvinceLoading").show();
	$("#updateProvinceState").empty();
	$.ajax({
		url:"/index.php/Wyn/Index/proviceInsert",
		type:"post",
		success:function(data){
			$("#updateProvinceLoading").hide();
			$("#updateProvinceState").html("更新成功！"+data.num+" " + data.errList+" 更新"+data.updateNum+" 新加"+data.addNum);
		},
	});
});
})
</script>
</head>

<body>
<h1><center>省份更新</center></h1>
<div style="width:800px; margin:0 auto;">
<div><input type="button" id="updateProvince" value="1.更新省份：" /><label style="color:red" id="updateProvinceState"></label><span id="updateProvinceLoading" style="display:none"><img src="/Public/images/loading.gif" height="16" /></span></div>
</div>


</body>
</html>