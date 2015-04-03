<?php if (!defined('THINK_PATH')) exit();?> <!doctype html>
<html>
<meta charset="utf-8" />
<head>
<title>系统配置项</title>
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-theme.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-theme.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-responsive.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/qnr/css/qnr.css" />
</head>
<body>
<!--sidebar-menu-->
<div id="sidebar">
  <ul>
	<li class="title">去哪儿蜘蛛</li>
    <li><a href="<?php echo U('Index/index');?>"><i class="glyphicon glyphicon-home"></i> <span>首页</span></a> </li>
    <li class="active"> <a href="<?php echo U('Sysconfig/index');?>"><i class="glyphicon glyphicon-transfer"></i> <span>代理IP</span></a> </li>
    <li> <a href="#"><i class="glyphicon glyphicon-cog"></i> <span>系统配置</span></a> </li>
    <li><a href="#"><i class="glyphicon glyphicon-floppy-save"></i> <span>数据导出</span></a></li>
  </ul>
</div>

<div id="content">
</div>
</body>
<script lanuage="javascript" type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
<script lanuage="javascript" type="text/javascript" src="/Public/bootstrap/js/bootstrap.min.js"></script>
<script lanuage="javascript" type="text/javascript" src="/Public/qnr/js/js.js"></script>

</html>