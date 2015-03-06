<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>批量添加链接</title>
		<style>form {margin: 0;}textarea {display: block;}</style>
		<!-- <link rel="stylesheet" href="/Public/editor/themes/default/default.css" /> -->
		<link rel="stylesheet" href="/Public/index.css" />
		<script charset="utf-8" src="/Public/editor/js/kindeditor-min.js"></script>
		<script charset="utf-8" src="/Public/editor/lang/zh_CN.js"></script>
		<script>
//kediter配置
var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name="content"]', {
		allowFileManager : true,
		newlineTag:"p",
		themesPath:"/Public/editor/themes/",
		items:['source', 'fullscreen', 'preview','|', 'undo', 'redo', 'cut', 'copy', 'paste', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull',
		'|', 'bold', 'italic', 'underline', 'removeformat', '|', 'link', 'unlink','clearhtml', '|']
		//,designMode:false
	});
	var sync = K('<a href="javascript:void(0)" id="snycPop" style="color:blue;text-decoration:undeline">同步官网新上线酒店</a>');
	K(".ke-toolbar").append(sync);
	K("#snycPop").bind("click", function(){
		var dialog = K.dialog({
				width : 640,
				title : '同步官网新上线酒店',
				body : '<div id="syncState" style="margin:10px;margin-right:0; height:320px; overflow-y:scroll"><p align="center">同步进程</p>'+
				''+
				'</div>'
				,
				closeBtn : {
						name : '关闭',
						click : function(e) {
								dialog.remove();
						}
				},
				yesBtn : {
						name : '开始同步',
						click : function(e) {
								alert(this.value);
						}
				},
				noBtn : {
						name : '终止操作',
						click : function(e) {
								dialog.remove();
						}
				}
		});
	});
	
	
});	
		</script>
		<!-- <script charset="utf-8" src="/Public/js/frontEnd.js"></script>	 -->
        <script type="text/javascript" language="javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
		<script language="javascript" type="text/javascript">
$(function(){
//清除编辑器内链接
function ClearLink(){
	var bd=$(window.frames["tsloveme"].document).find("body");
	var bdinner=bd.html();
	bdinner=bdinner.replace(/(\<a[^\>]+\>)|(\<\/a\>)/igm,"");
	bd.html(bdinner);
	}
//加链接代码：传入的json对象和 链接类型
function addLink(json,linkType){
	var bd = $(window.frames["tsloveme"].document).find("body");
	var bdinner = bd.html();
	var makeHotelLink = function(n,name){
		switch (linkType){
			case "wap":
				return '<a href="http://wap.wyn88.com/Hotel/HotelDetails?hotelcode='+json[n].hotelpmscode+'">'+name+'</a>';
				break;
			default:
				return '<a href="http://www.wyn88.com/resv/hotel_'+json[n].hotelpmscode+'.html">'+name+'</a>';
		}
	}
	$.each(json,function(i){
		var fullName = json[i].hotelname;
		var shortName = fullName.replace(/([\u4e00-\u9fa5]*?\（)|）/g,"");
		var regFull = new RegExp(fullName+"(?![\s\n]*?\<\/a>)","mg");
		var regShort = new RegExp(shortName+"(?![）]?[\s\n]*?\<\/a>)","mg");
		//全名匹配
		if(regFull.test(bdinner)){
			bdinner = bdinner.replace(regFull,makeHotelLink(i,fullName));
			//bd.html(bdinner);
			//alert(bdinner);
		}
		//简名匹配
		if(regShort.test(bdinner)){
			bdinner = bdinner.replace(regShort,makeHotelLink(i,shortName));
			//alert(bdinner);
			//bd.html(bdinner);
		}
	});
	bd.html(bdinner);

	
}

//链接按钮
$("#portal").click(function(){
	var linktype = $("input[name='linktype']:checked").val();
	$.ajax({
		url:'/index.php/Wyn/AddLink/getHotelCode',
		type:'POST',
		data:{type:'hotelPmsCode'},
		dataType:'json',
		success:function(data){
			addLink(data,linktype);
		}
	})
})

//清除链接
$("#clearLink").click(function(){
	ClearLink();
})


});

//链接弹窗搜索酒店。。。。
function searchHotel(){
	var keyword1=$("#sKeyWorld").val()
	if(!($.trim(keyword1))){
		alert("关键词不能为空");
		return;
	}
	$.ajax({
		url:"/index.php/Wyn/AddLink/searchHotel",
		type:"POST",
		data:{keyword:keyword1},
		dataType:'json',
		success:function(data){
			if(data==undefined){
				$("#SearchResult").empty().html('<p style="text-align:center;pading:25px;">没有搜索到相关酒店！</p>');
			}
			//判断链接类型
			var makeHotelLink = function(code,linkType){
				switch (linkType){
					case "wap":
						return 'http://wap.wyn88.com/Hotel/HotelDetails?hotelcode='+code;
						break;
					default:
						return 'http://www.wyn88.com/resv/hotel_'+code+'.html';
				}
			}
			var str="";
			$.each(data,function(i){
				var name = data[i].hotelname.replace(/([\u4e00-\u9fa5]*?\（)|）/g,"");
				str += '<a href="javascript:void(0)" v="'+data[i].hotelpmscode+'">'+name+'</a>';
			});
			$("#SearchResult").empty().append(str);
			$("#SearchResult").find("a").click(function(){
				if($(this).hasClass("selected")){return}
				$(this).siblings().removeClass("selected");
				$(this).addClass("selected");
				var code = $(this).attr("v");
				var linktype = $("input[name='linktype']:checked").val();
				var linkStr=makeHotelLink(code,linktype);
				$("#keUrl").val(linkStr);
				$("#testa").attr("href",linkStr);
			});
		}/*,
		error:function(){
			$("#SearchResult").empty().html('<p style="align:center;pading:25px;">没有搜索到相关酒店！</p>');
		}*/
	})
}
		


</script>

</head>
<body>
<div class="contPannel" style="display:block">
<input type="hidden" id="hotelUpdateState" value="1" />

	<h2>维也纳酒店批量添加链接,请把excel内容粘贴入内，再执行操作！</h2>
	
	<textarea name="content" style="width:100%;height:500px;visibility:hidden;"><table style="border-collapse:collapse;width:306pt" height="200" width="408"><tbody><tr><td class="et2" style="color:#000;font-size:12pt;font-weight:700;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid gray;background:#FC0" height="24" width="72">序号</td><td class="et2" style="color:#000;font-size:12pt;font-weight:700;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid gray;background:#FC0" height="24" width="203">分店名称</td><td class="et3" style="color:#000;font-size:12pt;font-weight:700;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid gray;background:#FC0" height="24" width="133">页面展示价格</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">1</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳会展中心店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">230元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">2</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳龙华人民南路店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">190元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">3</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳南山亿利达店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">274元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">4</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳沙井上南店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">138元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">5</td><td class="et7" style="color:red;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳观湖园店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">168元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">6</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳龙岗李朗店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">99元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">7</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳福永新田店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">99元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">8</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">广州机场②店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">182元起</td></tr></tbody></table></textarea>
	
	
	<div style="padding:20px 2px 0 0;">
	<b>链接类型：</b> 
	<input type="radio" name="linktype" value="pms" checked="checked" style="margin-left:15px;" />
	<label>官网</label> 
	<input type="radio" name="linktype" value="wap"" style="margin-left:15px;" />
	<label>WAP</label> 
	</div>
	<div style="padding:8px 2px;">
	<input type="button" value="添加官网链接" class="btn" id="portal" />
	<input type="button" value="清除所有链接" class="btn btnr" id="clearLink" />
	</div>
</div>


</body>
</html>