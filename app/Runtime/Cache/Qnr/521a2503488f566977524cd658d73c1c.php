<?php if (!defined('THINK_PATH')) exit();?> <!doctype html>
<html>
<meta charset="utf-8" />
<head>
<title>tester</title>
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-theme.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-theme.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/bootstrap/css/bootstrap-responsive.min.css" />
<link rel="styleSheet" type="text/css" href="/Public/qnr/css/qnr.css" />
<script lanuage="javascript" type="text/javascript" src="/Public/js/jquery-2.1.3.min.js"></script>
<script lanuage="javascript" type="text/javascript" src="/Public/bootstrap/js/bootstrap.min.js"></script>
<script lanuage="javascript" type="text/javascript" src="/Public/qnr/js/js.js"></script>
</head>
<body>
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-th"></i>Tables</a>
  <ul>
	<li class="title">XXX</li>
    <li class="active"><a href="<?php echo U('Index/index');?>"><i class="glyphicon glyphicon-home"></i> <span>首页</span></a> </li>
    <li> <a href="<?php echo U('Ipconfig/index');?>"><i class="glyphicon glyphicon-transfer"></i> <span>代理IP</span></a> </li>
     <li> <a href="<?php echo U('Gethotel/index');?>"><i class="glyphicon glyphicon-cloud-download"></i> <span>更新酒店</span></a>
    <li><a href="#"><i class="glyphicon glyphicon-floppy-save"></i> <span>数据导出</span></a></li>
<!--     <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>表单</span> </a>
      <ul>
        <li><a href="#">基本表单</a></li>
        <li><a href="#">带验证的表单</a></li>
        <li><a href="#">带提示的表单</a></li>
      </ul>
    </li> 
    <li class="content"> <span>每个月带宽</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: 77%;" class="bar"></div>
      </div>
      <span class="percent">77%</span>
      <div class="stat">21419.94 / 14000 MB</div>
    </li>-->
  </ul>
</div>

<div id="content">
	<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<h3>让蜘蛛去爬吧！</h3>
			<p style="font-size:1.2em">
			建议执行此动作前先去获取最新的IP地址。才能获得更好的爬行速度。
			</p>
			<button class="btn btn-primary btn-lg" id="getPrice" autocomplete="off"> 去爬数据 </button>
			
			<div class="progress progress-striped active" id="processBar" style="display:none">
			   <div class="progress-bar" role="progressbar" style="width:0%;">
				  <span class="sr-only"></span>
			   </div>
			</div>
			
						
			<!-- 模态框（Modal） -->
			<div class="modal fade" id="complete" tabindex="-1" role="dialog" 
			   aria-labelledby="myModalLabel" aria-hidden="true">
			   <div class="modal-dialog">
				  <div class="modal-content">
					 <div class="modal-header">
						<button type="button" class="close" 
						   data-dismiss="modal" aria-hidden="true">
							  &times;
						</button>
						<h4 class="modal-title" id="myModalLabel">
						   信息提示！
						</h4>
					 </div>
					 <div class="modal-body">
						 报价数据爬行完成
					 </div>
					 <div class="modal-footer">
						<button type="button" class="btn btn-default" 
						   data-dismiss="modal">关闭
						</button>
					 </div>
				  </div><!-- /.modal-content -->
			</div><!-- /.modal -->
			
			
			

			
		</div>
	</div>
	</div>

</div>
</body>

</html>