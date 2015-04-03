<?php if (!defined('THINK_PATH')) exit();?> <!doctype html>
<html>
<meta charset="utf-8" />
<head>
<title>更新代理IP</title>
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
	$("#updateIp").click(function(){
		var _this = $(this);
		_this.attr("disabled","disabled").text("正在更新...");
		$("#processBar").show();
		var bar = $("#processBar .progress-bar").get(0);
		bar.style.width='0.0%';
		var timer = setInterval(function(){
			var width = parseInt(bar.style.width);
			width = width+1+'%';
			bar.style.width=width;
		},200);
		$.ajax({
			url:"/index.php/Qnr/Ipconfig/getIpFromWeb",
			type:"GET",
			success:function(data){
				clearInterval(timer);
				var width =parseInt(parseInt(bar.style.width));
				if(width<100){
					$("#processBar .progress-bar").animate({width:"100%"},400,function(){
						_this.html(' 更新代理IP ')
						.removeAttr("disabled");
						$("#processBar").hide();
						$("#statefinal .alert-link span").text(data.num);
						$("#statefinal").show();
						
					});
				}
				else{
					_this.html(' 更新代理IP ')
					.removeAttr("disabled");
					$("#processBar").hide();
					$("#statefinal .alert-link span").text(data.num);
					$("#statefinal").show();	
				}
				
			}
		});
		
	})
})
</script>
</head>
<body>
<!--sidebar-menu-->
<div id="sidebar">
  <ul>
	<li class="title">XXX</li>
    <li><a href="<?php echo U('Index/index');?>"><i class="glyphicon glyphicon-home"></i> <span>首页</span></a> </li>
    <li class="active"> <a href="<?php echo U('Ipconfig/index');?>"><i class="glyphicon glyphicon-transfer"></i> <span>代理IP</span></a> </li>
     <li> <a href="<?php echo U('Gethotel/index');?>"><i class="glyphicon glyphicon-cloud-download"></i> <span>更新酒店</span></a>
    <li><a href="#"><i class="glyphicon glyphicon-floppy-save"></i> <span>数据导出</span></a></li>
  </ul>
</div>

<div id="content">
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<h3>更新代理IP地址</h3>
			<p style="font-size:1.2em">更新按钮建议至少隔3秒按一次，否则去哪儿网会把IP资源屏蔽掉。这是去其他网站上爬出的免费代理IP。蜘蛛程序的爬行就靠它们了。
			</p> 
			<button class="btn btn-primary btn-lg" id="updateIp" autocomplete="off"> 更新代理IP </button>
			<br /><br />
			
			<div class="progress progress-striped active" id="processBar" style="display:none">
			   <div class="progress-bar" role="progressbar" style="width:0%;">
				  <span class="sr-only"></span>
			   </div>
			</div>
			
			<div class="alert alert-success alert-dismissable" id="statefinal" style="display:none">
			   <a href="<?php echo U('Index/index');?>" class="alert-link">获取了<span style="font-size:.75em"></span>个可用的Ip地址，点击<u>[首页]</u>去抓取房价数据吧！</a>
			</div>
			

		</div>
	</div>
</div>
</div>
</body>

</html>