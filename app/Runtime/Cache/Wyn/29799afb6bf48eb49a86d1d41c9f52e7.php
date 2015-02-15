<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>菜单头部</title>
		<link rel="stylesheet" href="/Public/index.css" />
        <script type="text/javascript" language="javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
		<script language="javascript" type="text/javascript">
$(function(){
	$("header ul li").click(function(){
		if($(this).hasClass("selected")) return;
		else{
			$(this).siblings().removeClass("selected");
			$(this).addClass("selected");
			parent.document.title=$(this).text();
		}
	});
})
		
		</script>

</head>
<body>
<header>
	<div class="logo"></div>
	<ul>
		<li class="selected"><a href="/index.php/Wyn/AddLink" target="content">酒店批量加链接</a></li>
		<li><a href="/index.php/Wyn/Index/datasync" target="content">同步官网数据</a></li>
		<!-- <li><a href="" target="content">表格/数据导入导出</a></li>
		<li><a href="" target="content">酒店数据查询</a></li> -->
	</ul>
</header>

</body>
</html>