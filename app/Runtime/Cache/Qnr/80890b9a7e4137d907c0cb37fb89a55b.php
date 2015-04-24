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
<script>
$(function(){
	$("#getdata").click(function(){
		$("#datasearch").submit();
	});
});

</script>
</head>
<body>
<div id="iframe" style="position:fixed; right:0;top:0; width:600px; height:480px; z-index:9999; visibility:hidden"></div>
<div style="width:50px; height:50px; position:fixed; right:0; top:0; z-index:99999" id="iframeToggle"></div>
<form id="priceData" style="display:none"></form>
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-th"></i>Tables</a>
  <ul>
	<li class="title">房价倒挂监测</li>
    <li><a href="<?php echo U('Index/index');?>"><i class="glyphicon glyphicon-home"></i> <span>首页</span></a> </li>
    <li> <a href="<?php echo U('Ipconfig/index');?>"><i class="glyphicon glyphicon-transfer"></i> <span>代理IP</span></a> </li>
     <li> <a href="<?php echo U('Gethotel/index');?>"><i class="glyphicon glyphicon-cloud-download"></i> <span>更新酒店</span></a>
    <li class="active"><a href="<?php echo U('Pricedata/index');?>"><i class="glyphicon glyphicon-floppy-save"></i> <span>数据导出</span></a></li>

  </ul>
</div>

<div id="content">
	<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<form role="form" class="form-inline" action="/index.php/Qnr/Pricedata/search" id="datasearch" method="post">
			  <div class="form-group ">
				<label for="timeid">选择已抓取的记录</label>
				<select class="form-control" name="timeid">
				<?php if(is_array($selects)): $i = 0; $__LIST__ = $selects;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s1): $mod = ($i % 2 );++$i;?><option value="<?php echo ($s1["id"]); ?>"><?php echo ($s1["time"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			  </div>
			  <div class="form-group">
				<input type="checkbox" class="checkbox" name="qnr" checked="checked" />
				<lable for="qnr">去哪儿价</lable>
			  </div>
			  <div class="form-group">
				<input type="checkbox" name="ctrip"  />
				<lable for="ctrip">携程价</lable>
			  </div>
			  <div class="form-group">
				<input type="checkbox" name="elong" />
				<lable for="elong">艺龙价</lable>
			  </div>
			  <div class="form-group">
				<input type="checkbox" class="disabled" name="wyn" checked="checked"  />
				<lable for="wyn">官网价</lable>
			  </div>
			   <div class="form-group" style="padding-left:25px;">
				<input type="checkbox" class="disabled" name="fanli" />
				<lable for="fanli">返利</lable>
			  </div>
			  
			 </form>
			 <br /> 
			<div><button class="btn btn-primary" id="getdata" autocomplete="off"> 查询数据 </button>
			</div> <br />
			
			<div id="dataBox">
				<table class="table table-condensed table-bordered">
				
				<thead>
				    <?php if($time): ?><tr><th colspan="6" align="center"><p><?php echo ($time); ?>抓取到的数据如下</p></th></tr>
					<?php else: endif; ?>
				<tr>
				<th>编 号</th>
				<th>酒 店</th>
				<th>房 型</th>
				<th>去哪儿价格</th>
				<th>官网价格</th>
				<th>qunar.com</th>
				</tr>
				</thead>
				<tbody>
				<?php if(is_array($prices)): $i = 0; $__LIST__ = $prices;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p1): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($p1["id"]); ?></td>
				<td><?php echo ($p1["hotelname"]); ?></td>
				<td><?php echo ($p1["roomname"]); ?></td>
				<td><?php echo ($p1["priceqnr"]); ?></td>
				<td><?php echo ($p1["pricewyn"]); ?></td>
				<td><a href="http://hotel.qunar.com/city/<?php echo ($p1["hotelseq"]); ?>" target="_blank">查看&gt;&gt;</a></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
				</table>
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
			<p></p>
			<div class="cont">
				<ul id="hotelUpdateInfo"></ul>
			</div>
			<div class="cont">
				
			</div>
			
	</div>
	</div>

</div>
</body>

</html>