<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>酒店列表更新程序</title>
<link rel="stylesheet" type="text/css" href="/Public/index.css" />
<script  language="javascript" type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
//获取json对象数组长度
function getJsonObjLength(jsonObj) {
    var Length = 0;
    for (var item in jsonObj) {
      Length++;
    }
    return Length;
  }
//省份更新
$("#updateProvince").click(function(){
	$("#updateProvinceLoading").show();
	$("#updateProvinceState").empty();
	$.ajax({
		url:"/index.php/Wyn/Index/proviceInsert",
		type:"GET",
		success:function(data){
			//alert(111);
			$("#updateProvinceLoading").hide();
			$("#updateProvinceState").html("更新成功！"+data.num+" " + data.errList+" 更新"+data.updateNum+" 新加"+data.addNum);
		},
	});
});
//城市更新
$("#updateCity").click(function(){
	$("#updateCity").parent().children(".loading").show();
	$("#updateCity").parent().children(".state").empty();
	$.ajax({
		url:"/index.php/Wyn/Index/updateCity",
		type:"GET",
		success:function(data){
			//alert(1222);
			$("#updateCity").parent().children(".loading").hide();
			$("#updateCity").parent().children(".state").html("更新成功！" +data.num);
		},
	});
});
//获取城市列表信息并逐一更新酒店。
$("#updatehotel").click(function(){
	$("#hotelUpdateState").val("1");
	$("#updatehotel").parent().children(".loading").show();
	$("#updatehotel").parent().children(".state").empty();
	$.ajax({
		url:"/index.php/Wyn/Index/getcity",
		type:"GET",
		success:function(data){
			//alert(data);
			updateByCity(data,1);
		},
		error:function(){
			$("#updatehotel").parent().children(".state").html("获取城市列表失败！");
		}
	});
});

//更新单个城市酒店
function updateByCity(data,i){
	if(!$("#hotelUpdateState").val()){
		alert("用户中止！")
	}
	//alert(data[i].cityid+" "+data[i].cityno+" "+decodeURIComponent(data[i].cityname));
	$("#updatehotel").parent().children(".state").text("正在更新"+ data[i]["cityname"]);
	$.ajax({
		url:"/index.php/Wyn/Index/updateHotel",
		type:"POST",
		/*data:{
		cityId:17,
		cityNo:4401,
		cityName:'广州市'
		},*/
		data:{
		cityId:data[i].cityid,
		cityNo:data[i].cityno,
		cityName:decodeURIComponent(data[i].cityname)
		},
		success:function(da){
			i+=1;
			if(i<getJsonObjLength(data)){
				$("#updatehotel").parent().children(".state").text("更新完成！"+da.num+"条");
				setTimeout(function(){updateByCity(data,i)},200);
			}
			else{
				$("#updatehotel").parent().children(".loading").hide();
				$("#updatehotel").parent().children(".state").text("全部更新完成！");			
			}
			
			
		}
	});

}


})
</script>
</head>
<body>
<div class="contPannel">
<h2>维也纳省份城市酒店更新</h2>
<input type="hidden" id="hotelUpdateState" value="1" />
	<div>
		<input type="button" id="updateProvince" value="1.更新省份：" />
		<span id="updateProvinceLoading" style="display:none"><img src="/Public/images/loading.gif" height="16" /></span>
		<label style="color:red" id="updateProvinceState"></label>
		
	</div>
	<br /> <br />
	<div>
		<input type="button" id="updateCity" value="2.更新城市：" />
		<span class="loading" style="display:none"><img src="/Public/images/loading.gif" height="16" /></span>
		<label style="color:red" class="state"></label>	
	</div>
	<br /> <br />
	<div>
		<input type="button" id="updatehotel" value="3.酒店更新：" />
		<span class="loading" style="display:none"><img src="/Public/images/loading.gif" height="16" /></span>
		<label style="color:red" class="state"></label>
		
	</div>

</div>


</body>
</html>