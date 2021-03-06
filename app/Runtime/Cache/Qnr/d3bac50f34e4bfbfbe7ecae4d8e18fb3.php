<?php if (!defined('THINK_PATH')) exit();?> <!doctype html>
<html>
<meta charset="utf-8" />
<head>
<title>更新酒店</title>
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-theme.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-theme.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-responsive.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/qnr/css/qnr.css" />

<script lanuage="javascript" type="text/javascript" src="/Public/js/jquery-2.1.3.min.js"></script>
<script lanuage="javascript" type="text/javascript" src="/Public/bootstrap/js/bootstrap.min.js"></script>
<script lanuage="javascript" type="text/javascript" src="/Public/qnr/js/js.js"></script>
<script lanuage="javascript" type="text/javascript">
$(function(){
	//json长度
	function getJsonLength(jsonData){
		var jsonLength = 0;
		for(var item in jsonData){
			jsonLength++;
		}
		return jsonLength;
	}
	//更新城市
	$("#updateCity").click(function(){
		var _this = $(this);
		$(".btnGroup button").attr("disabled","disabled");
		_this.text("正在更新...");
		$("#processBar").show();
		var bar = $("#processBar .progress-bar").get(0);
		bar.style.width="0.0%";
		$.ajax({
			url:"/index.php/Qnr/Gethotel/getCity",
			type:"GET",
			success:function(data){
				var citys = data.citys;
				//var ipids = data.ipids;
				var cityLen = getJsonLength(data.citys);
				//var ipidLen = getJsonLength(data.ipids);
				var i=0,j=0;
				function validate(){
					var ajax = $.ajax({
						url:"/index.php/Qnr/Gethotel/store",
						data:{"cityurl":citys[i].cityurl,"name":citys[i].name/*,"ipid":ipids[j]*/},
						type:"POST",
						timeout:12000,
						success:function(data){
							bar.style.width=100*(i+1)/cityLen+"%";
							i++;
							//j++;
							//if (j>=ipidLen){j=0}
							if(i<cityLen){
								setTimeout(validate,600);
							}
							else{
								_this.text(' 更新城市 ');
								$(".btnGroup button").removeAttr("disabled");
								$("#processBar").hide();
							}
						}/*,
						complete:function(XMLHttpRequest,status){
							if(status=='timeout'){
								ajax.abort();
								ipids.splice(j,1);
								ipidLen = getJsonLength(data.ipids);
								setTimeout(validate,600);
							}
						}*/
					});
				};
				validate();}
		});
	});
		
		//酒店更新入库
	$("#updateHotel").click(function(){
		var _this = $(this);
		$(".btnGroup button").attr("disabled","disabled");
		_this.text("正在更新...");
		$("#processBar").show();
		var bar = $("#processBar .progress-bar").get(0);
		bar.style.width="0.0%";
		var ul =$("#hotelUpdateInfo");
		ul.empty().show();
		$("<li>正在获取城市信息...</li>").prependTo(ul);
		$.ajax({
			url:"/index.php/Qnr/Gethotel/getHotelByCity",
			type:"GET",
			success:function(data){
				$("<li>获取城市成功！</li><li>正在更新酒店...</li>").prependTo(ul);
				//var citys = data.citys;
				//var ipids = data.ipids;
				//var cityLen = getJsonLength(data.citys);
				//var ipidLen = getJsonLength(data.ipids);
				var citys = data;
				var cityLen = getJsonLength(citys);
				var i=0/*,j=0*/;
				var page =1;
				function validate(){
					if(!page){
						$("<li>正在更新"+citys[i].cityname+"的酒店...</li>").prependTo(ul);
						page = 1;
					}
					var ajax = $.ajax({
						url:"/index.php/Qnr/Gethotel/storeHotel",
						data:{"cityurl":citys[i].cityurl,"page":page},
						type:"POST",
						timeout:12000,
						success:function(data){
							page = data.page;
							if(!page){
								bar.style.width=100*(i+1)/cityLen+"%";
								$("<li>"+citys[i].cityname+"酒店更新成功！</li>").prependTo(ul);
								i++;
							}
							//j++;
							//if (j>=ipidLen){j=0}
							if(i<cityLen){
								setTimeout(validate,600);
							}
							else{
								_this.text(' 更新酒店 ');
								$(".btnGroup button").removeAttr("disabled");
								$("#processBar").hide();
							}
						}/*,
						complete:function(XMLHttpRequest,status){
							if(status=='timeout'){
								ajax.abort();
								ipids.splice(j,1);
								ipidLen = getJsonLength(data.ipids);
								setTimeout(validate,400);
							}
						}*/
					});
				};
				validate();
		}
	});
})
		
});

</script>
</head>
<body>
<!--sidebar-menu-->
<div id="sidebar">
  <ul>
	<li class="title">房价倒挂监测</li>
    <li><a href="<?php echo U('Index/index');?>"><i class="glyphicon glyphicon-home"></i> <span>首页</span></a> </li>
    <li> <a href="<?php echo U('Ipconfig/index');?>"><i class="glyphicon glyphicon-transfer"></i> <span>代理IP</span></a> </li>
    <li class="active"><a href="<?php echo U('Gethotel/index');?>"><i class="glyphicon glyphicon-cloud-download"></i> <span>更新</span></a> </li>
    <li><a href="<?php echo U('Pricedata/index');?>"><i class="glyphicon glyphicon-floppy-save"></i> <span>数据导出</span></a></li>
  </ul>
</div>

<div id="content">
<div class="container-fluid">
	<div class="row-fluid btnGroup">
		<div class="span6">
			<h3>更新城市列表</h3>
			<p style="font-size:1.2em">同步城市列表，同步去哪儿线上有 "维也纳" 的城市。
			</p> 
			<button class="btn btn-primary btn-lg" id="updateCity" autocomplete="off"> 更新城市 </button>
		</div>
		<div class="span6">
			<h3>更新线上酒店</h3>
			<p style="font-size:1.2em">按照一个个城市同步去哪儿线上的所有维也纳酒店，
			</p> 
			<button class="btn btn-primary btn-lg" id="updateHotel" autocomplete="off"> 更新酒店 </button>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<br /><br />
			
			<div class="progress progress-striped active" id="processBar" style="display:none">
			   <div class="progress-bar" role="progressbar" style="width:0%;">
				  <span class="sr-only"></span>
			   </div>
			</div>
			
			
			<ul id="hotelUpdateInfo" style="display:none">
				
			</ul>
			

		</div>
	</div>
</div>
</div>
</body>

</html>