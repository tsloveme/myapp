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
	//酒店报价数据入库更新
	$("#getPrice").click(function(){
		var _this = $(this);
		_this.attr("disabled","disabled").text("数据抓取中...");
		$("#processBar").show();
		var bar = $("#processBar .progress-bar").get(0);
		bar.style.width="0.0%";
		var ul =$("#hotelUpdateInfo");
		ul.empty().show();
		$("<li>正在获取酒店信息</li>").prependTo(ul);
		$.ajax({
			url:"/index.php/Qnr/Index/getHotel",
			type:"GET",
			success:function(data){
				$("<li>成功获取酒店信息！</li><li>开始抓取报价...</li>").prependTo(ul);
				var hotel = data.hotel;
				var hotelLen = getJsonLength(data.hotel);
				var i=0;
				/*function getPriceInit(){
					var ajax_start = $.ajax({
						url:"/index.php/Qnr/Index/priceUnitInit",
						type:"POST",
						data:{'seq':hotel[i].hotelseq},
						success:function(data){
							//alert(data);
							//alert(data[1]);
						}
					})
				}	*/
				//makeIframe('http://hotel.qunar.com/city/shenzhen/dt-8524/');
				makeIframe(0);
				function storePrice(){
					if($("#priceData textarea").length > 0){
						$("<li>正在抓取"+hotel[i].hotelname+"的报价</li>").prependTo(ul);
						$.ajax({
							url:"/index.php/Qnr/Index/storePrice",
							type:'POST',
							data:{'seq':hotel[i].hotelseq,'pricedata':$("#priceData textarea").eq(0).val(),'timeid':data.timeId},
							success:function(){
								$("<li>抓取成功！！！</li>").prependTo(ul);
								bar.style.width=100*(i+1)/hotelLen+"%";
								$("#priceData textarea").eq(0).remove();
								if (++i < hotelLen){
									/*setTimeout(function(){
										if($("#priceData textarea").length > 0){
											storePrice(j);
										}
										else{
											setTimeout(arguments.callee,400);
										}
									},100);*/
									storePrice(i);
								}
								else{
									$("<li>全部抓取成功！！！</li>").prependTo(ul);
									_this.text(' 更新城市 ');
									$("#getPrice").removeAttr("disabled");
									$("#processBar").hide();
								}
							}
						});
					}
					else{
						setTimeout(arguments.callee,400);
					}

				}
				storePrice(0);
				function makeIframe(j){
					var j = j || 0;
					var src ="http://hotel.qunar.com/city/"+(hotel[j].hotelseq.replace(/_(?=\d+)/,'/dt\-'))+"\/\?";
					//var src = "/index.php/Qnr/Index/?seq="+hotel[j].hotelseq;
					$("#iframe").empty();
					var dom = $('<iframe src="'+src+'" name="iframe" width="100%" height="100%"><\/iframe>');
					dom.get(0).onload = function(){
					var QNR = window.iframe.window.$;
						QNR(".btn_openPrc").click();
						var prices = [];
						function timmer(){
							if((QNR("#QunarPopBoxBG").length > 0)||((QNR(".e_loading").length ==0)&&(QNR(".htl-type-list li").length==0))){
								//alert(1111);
								makeIframe(j);
								return;
							}
							var flag =true;
							QNR(".similar-type-agent-list").each(function(){
								if(QNR(this).children(".similar-type-agent-item").length < 1){
									flag = false;
								}
								});
							if(flag){
								setTimeout(arguments.callee,150);
							}
							else{
								QNR(".htl-type-list li").each(function(){
									_this = QNR(this);
									var roomType = QNR.trim(_this.find(".js-p-name").html());
									var priceWyn = _this.find("[alt='维也纳酒店官网直销']").parents("table").find(".count_pr").text();
									//priceWyn = priceWyn.replace(/[^\d]/gm,"");
									priceWynRet = priceWyn.match(/\d+/);
									priceWyn = priceWynRet ? priceWynRet[0] : 0;
									var priceQnr = _this.find("[src='http://userimg.qunar.com/imgs/201407/17/w3ovewzqCvgLjWiSHexact.jpg']").parents("table").find(".count_pr").text();
									//priceQnr = priceQnr.replace(/[^\d]/gm,"");
									priceQnrRet = priceQnr.match(/\d+/);
									priceQnr = priceQnrRet ? priceQnrRet[0] : 0;
									prices.push(['","roomType":"'+roomType,'","priceQnr":"'+priceQnr,'","priceWyn":"'+priceWyn].join(""));
								});
								prices = prices.join("");
								$("#priceData").append("<textarea>"+prices+'"'+"<\/textarea>");
								//alert(prices);
								if (++j < hotelLen){
									makeIframe(j);
								}
							}
							
						 }
						 timmer();
						 //setTimeout(timmer,150);
						}
					$("#iframe").append(dom);
				}
		}
	});
});

$("#iframeToggle").hover(
	function(){
		$("#iframe").css("visibility","visible");
	},
	function(){
		$("#iframe").css("visibility","hidden");
	}
);

//iframe 递归取数据;
/*function makeIframe(j){
	var j = j || 0;
	alert(hotel[j].hotelseq);
	var src ="http://hotel.qunar.com/city/"+(hotel[j].hotelseq.replace(/_(?=\d+)/,'/dt\-'))+"\/\?";
	$("#iframe").empty();
	var dom = $('<iframe src="'+src+'" name="iframe"><\/iframe>');
	dom.get(0).onload = function(){
	var QNR = window.iframe.window.$;
		QNR(".btn_openPrc").click();
		var prices = [];
		function timmer(){
			var flag =true;
			QNR(".similar-type-agent-list").each(function(){
				if(QNR(this).children(".similar-type-agent-item").length < 1){
					flag = false;
				}
				});
			if(flag){
				setTimeout(arguments.callee,150);
			}
			else{
				QNR(".htl-type-list li").each(function(){
					_this = QNR(this);
					var roomtype = QNR.trim(_this.find(".js-p-name").html());
					var priceWyn = _this.find("[alt='维也纳酒店官网直销']").parents("table").find(".count_pr").text();
					//priceWyn = priceWyn.replace(/[^\d]/gm,"");
					priceWynRet = priceWyn.match(/\d+/);
					priceWyn = priceWynRet ? priceWynRet[0] : 1;
					var priceQnr = _this.find("[src='http://userimg.qunar.com/imgs/201407/17/w3ovewzqCvgLjWiSHexact.jpg']").parents("table").find(".count_pr").text();
					//priceQnr = priceQnr.replace(/[^\d]/gm,"");
					priceQnrRet = priceQnr.match(/\d+/);
					priceQnr = priceQnrRet ? priceQnrRet[0] : 1;
					prices.push(['","roomtype":"'+roomtype,'","priceQnr":"'+priceQnr,'","priceWyn":"'+priceWyn].join(""));
				});
				prices = prices.join("");
				$("#priceData").append("<textarea>"+prices+"<\/textarea>");
				if (++j < hotelLen){
					makeIframe(j);
				}
			}
			
		 }
		 timmer();
		 //setTimeout(timmer,150);
		}
	$("#iframe").append(dom);
}*/


/*
$.ajax({
	url:'/index.php/Qnr/Index/priceRobot',
	data:{'P':[{"id":"8","roomname":"u8c6au534eu53ccu4ebau623f"},{"id":"9","roomname":"u8c6au534eu5355u4ebau623f"},{"id":"10","roomname":"u5546u52a1u6570u7801u623f"},{"id":"11","roomname":"u9ad8u7ea7u623f"},{"id":"12","roomname":"u5546u52a1u5355u4ebau623f"},{"id":"13","roomname":"u5bb6u5eadu666fu89c2u623f"},{"id":"14","roomname":"u8c6au534eu5957u623f"},{"id":"15","roomname":"u6807u51c6u5355u4ebau623f(u65e0u7a97)"}],
	},
	method:"POST",
	success:function(){
		alert(data);
	}
})*/
		
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
    <li class="active"><a href="<?php echo U('Index/index');?>"><i class="glyphicon glyphicon-home"></i> <span>首页</span></a> </li>
    <li> <a href="<?php echo U('Ipconfig/index');?>"><i class="glyphicon glyphicon-transfer"></i> <span>代理IP</span></a> </li>
     <li> <a href="<?php echo U('Gethotel/index');?>"><i class="glyphicon glyphicon-cloud-download"></i> <span>更新酒店</span></a>
    <li><a href="<?php echo U('Pricedata/index');?>"><i class="glyphicon glyphicon-floppy-save"></i> <span>数据导出</span></a></li>
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
			<h3>去哪儿官网抓取数据</h3>
			<p style="font-size:1.2em">系统自动去去哪儿官网抓取所有维也纳酒店的数据，如有新酒店上线，请先去更新酒店。
			</p> 
			<button class="btn btn-primary btn-lg" id="getPrice" autocomplete="off"> 去获取最新房价 </button>
			<br /><br />
			<div class="progress progress-striped active" id="processBar" style="display:none">
			   <div class="progress-bar" role="progressbar" style="width:0%;">
				  <span class="sr-only"></span>
			   </div>
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