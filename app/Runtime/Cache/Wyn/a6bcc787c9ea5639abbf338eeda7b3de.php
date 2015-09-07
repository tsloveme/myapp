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
				width : 480,
				title : '同步官网新上线酒店',
				body : '<div id="syncState" style="margin:10px;margin-right:0; height:240px; overflow-y:scroll"><p style="padding:100px 10px 0 0; text-align:center; color:#ccc">同步结果将在这里展示。</p>'+
				''+
				'</div>'
				,
				closeBtn : {
						name : '关闭',
						click : function(e) {
							if($("#hotelUpdateState").val()=="1"){
								var b = confirm("正在更新操作，要退出吗？");
								if (b){
									$("#hotelUpdateState").val(" ");
									dialog.remove();
								}
							}
							else{
								dialog.remove();
							}
						}
				},
				yesBtn : {
						name : '开始同步',
						click : function(e) {
								$("#startUpdate").click();
						}
				},
				noBtn : {
						name : '终止操作',
						click : function(e) {
							$("#hotelUpdateState").val(" ");
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
		var fullName1 = json[i].hotelname.replace('(','\\\(').replace(')','\\\)');
		var shortName = fullName.replace(/([\u4e00-\u9fa5]*?\（)|([\u4e00-\u9fa5]*?\()|）|\(|\)/g,"");
		var regFull = new RegExp(fullName1+"(?![\\s\\n]*?\<\/a>)","mg");
		var regShort = new RegExp(shortName+"(?![）]?[\\s\\n]*?\<\/a>)","mg");
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
});


//获取json对象数组长度
function getJsonObjLength(jsonObj) {
    var Length = 0;
    for (var item in jsonObj) {
      Length++;
    }
    return Length;
  }
//省份更新
$("#startUpdate").click(function (){
	$("#hotelUpdateState").val("1")
	$(".ke-dialog-yes .ke-button").attr("disabled","disabled");
	$(".ke-dialog-footer").prepend('<img src="/Public/images/loading.gif" height="16" style="vertical-align:middle" />');
	$("#syncState").empty().append('<p><b>正在更新省份...</b></p>');
	$.ajax({
		url:"/index.php/Wyn/Index/proviceInsert",
		type:"GET",
		success:function(data){
			$("#syncState").append("<p>操作成功: 总数"+data.num+"条，更新"+data.updateNum+"条记录，新增"+data.addNum+"条记录</p>");
			updateCity();
		},
	});
});
//城市更新
function updateCity(){
	if($("#hotelUpdateState").val()==" "){
		$(".ke-dialog-yes .ke-button").removeAttr("disabled");
		$(".ke-dialog-footer img").remove();
		$("#syncState").append('<p><b style="color:red">同步过程被终止！！！</b></p>');
		return;
	}	$("#syncState").append('<p><b>正在更新城市...</b></p>');
	$.ajax({
		url:"/index.php/Wyn/Index/updateCity",
		type:"GET",
		success:function(data){
			$("#syncState").append("<p>操作成功: 更新"+data.num+"条记录</p>");
			updateHotel();
		},
	});
};
//获取城市列表信息并逐一更新酒店。
function updateHotel(){
	if($("#hotelUpdateState").val()==" "){
		$(".ke-dialog-yes .ke-button").removeAttr("disabled");
		$(".ke-dialog-footer img").remove();
		$("#syncState").append('<p><b style="color:red">同步过程被终止！！！</b></p>');
		return;
	}	$("#syncState").append('<p><b>获取城市列表逐一更新酒店...</b></p>');
	$.ajax({
		url:"/index.php/Wyn/Index/getcity",
		type:"GET",
		success:function(data){
			//alert(data);
			updateByCity(data,1);
		},
		error:function(){
			$("#syncState").append("<p>获取城市列表失败！</p>");
		}
	});
};

//更新单个城市酒店
/*function updateByCity(data,i){
	if($("#hotelUpdateState").val()==" "){
		$(".ke-dialog-yes .ke-button").removeAttr("disabled");
		$(".ke-dialog-footer img").remove();
		$("#syncState").append('<p><b style="color:red">同步过程被终止！！！</b></p>');
		return;
	}	$("#syncState").append('<p><b>正在更新 '+data[i]["cityname"]+' 的酒店...</b></p>');
	$.ajax({
		url:"/index.php/Wyn/Index/updateHotel",
		type:"POST",
		data:{
		cityId:data[i].cityid,
		cityNo:data[i].cityno,
		cityName:decodeURIComponent(data[i].cityname)
		},
		success:function(da){
			i+=1;
			if(i<getJsonObjLength(data)){
				$("#syncState").append("<p>操作成功！更新"+da.num+"条记录</p>")
				$("#syncState").scrollTop(5000);
				setTimeout(function(){updateByCity(data,i)},200);
			}
			else{
				$("#syncState").append('<p style="font-weight:bold; color:green">全部更新完成！</p>');
				$(".ke-dialog-yes .ke-button").removeAttr("disabled");
				$(".ke-dialog-footer img").remove();
				$("#hotelUpdateState").val(" ")
			}


		}
	});

}*/
    function updateByCity(data,i){
        if($("#hotelUpdateState").val()==" "){
            $(".ke-dialog-yes .ke-button").removeAttr("disabled");
            $(".ke-dialog-footer img").remove();
            $("#syncState").append('<p><b style="color:red">同步过程被终止！！！</b></p>');
            return;
        }	$("#syncState").append('<p><b>正在更新 '+data[i]["cityname"]+' 的酒店...</b></p>');

        //第几页码
        var page = 1;
        var addSuccess = 0;
        //单次页码抓取
        var singleAjax = function(){
            $.ajax({
                url:"/index.php/Wyn/Index/updateHotel",
                type:"POST",
                data:{
                    cityId:data[i].cityid,
                    cityNo:data[i].cityno,
                    cityName:decodeURIComponent(data[i].cityname),
                    pageI:page
                },
                success:function(da){
                    page+=1;
                    addSuccess+= da.num;
                    if(page<=da.url){
                        singleAjax();
                        return;
                    }
                    $("#syncState").append("<p>操作成功！更新"+addSuccess+"条记录</p>")
                    $("#syncState").scrollTop(5000);
                    //遍历下一个城市
                    i+=1;
                    if(i<getJsonObjLength(data)){
                        setTimeout(function(){updateByCity(data,i)},200);
                    }
                    else{
                        $("#syncState").append('<p style="font-weight:bold; color:green">全部更新完成！</p>');
                        $(".ke-dialog-yes .ke-button").removeAttr("disabled");
                        $(".ke-dialog-footer img").remove();
                        $("#hotelUpdateState").val(" ")
                    }


                }
            });
        }
        singleAjax();
    }
//更新过过程人为终止
function ifUpdateAbort(){

}
//end
});

/*******************链接弹窗搜索酒店***************************/
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
<input type="hidden" id="hotelUpdateState" value="" />
<input type="button" id="startUpdate" style="display:none" />

	<h2>维也纳酒店批量添加链接,请把excel内容粘贴入内，再执行操作！</h2>
	
	<textarea name="content" style="width:100%;height:500px;visibility:hidden;"><table style="border-collapse:collapse;width:306pt" height="200" width="408"><tbody><tr><td class="et2" style="color:#000;font-size:12pt;font-weight:700;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid gray;background:#FC0" height="24" width="72">序号</td><td class="et2" style="color:#000;font-size:12pt;font-weight:700;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid gray;background:#FC0" height="24" width="203">分店名称</td><td class="et3" style="color:#000;font-size:12pt;font-weight:700;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid gray;background:#FC0" height="24" width="133">页面展示价格</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">1</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳会展中心店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">230元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">2</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳龙华人民南路店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">190元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">3</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳南山亿利达店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">274元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">4</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳沙井上南店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">138元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">5</td><td class="et7" style="font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">  深圳火车店  <span style="font-size:9pt;color:#999">(这样的店和官网不一致，会加不上链接，选择 <span style="color:red"><strong style="color:red">火车</strong></span> 再选择工具栏上的链接图标试试)</span></td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">168元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">6</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳龙岗李朗店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">99元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">7</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">深圳福永新田店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">99元起</td></tr><tr><td class="et4" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="72">8</td><td class="et5" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="203">广州机场②店</td><td class="et6" style="color:#000;font-size:11pt;font-weight:400;font-style:normal;text-decoration:none;text-align:center;vertical-align:middle;border:.5pt solid #808080" height="22" width="133">182元起</td></tr></tbody></table></textarea>
	
	
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