$(function(){
	var strextention='\
<div class="cheatOpen"></div>\
<div class="cheat_cont" style="top:0">\
	<div class="main">\
		<table border="0" width="100%">\
			<tr>\
				<td align="right">选择答案:</td>\
				<td>\
				<div class="select" style="width:540px">\
					<select name="examItem" id="examItem">\
						<option value="0">----------------请点击获取最新答案列表----------------</option>\
					</select>\
				</div>\
				<span class="getListStatus">\
					<a href="javascript:void(0)">获取最新</a>\
					<img style="display:none" src="__PUBLIC__/images/loading.gif" />\
					<span></span>\
				</span>\
				</td>\
			</tr>\
			<tr>\
				<td></td>\
				<td><input type="button" class="cheatBtn" id="cheatNow" value="快速做题" />\
				<input type="text" Id="autoTime" vale="5" class="cheatText" style="width:48px;margin-left:50px;" /> <label>分钟后自动交卷</label>\
				<input type="button" class="cheatBtn" id="cheatConfirmTime" value="确 认" />\
				<label id="timeLeft"></label>\
				</td>\
			</tr>\
			<tr>\
				<td></td>\
				<td><span style="color:green">已清除Alt,Tab,Win键限制，请放心操作</span></td>\
			</tr>\
		</table>\
	</div>\
	<a href="javascript:void(0)" class="cheatClose">[ 收 起 ]</a>\
</div>';
	$("body").append(strextention);
	$(".cheatOpen").click(function(){
		$(this).animate({top:-31},function(){
			$(".cheat_cont").animate({top:0});
		})
	});
	
	$(".cheatClose").click(function(){
		$(".cheat_cont").animate({top:-121},function(){
			$(".cheatOpen").animate({top:0});
		});
	});
	function timeOutRun(){
		if(parseInt($("#autoTime").val())>30){
			alert("不能超过30分钟");
			return;
		}
		if(parseInt($("#autoTime").val())<1){
			alert("不能小于1分钟");
			return;
		}
		if(!parseInt($("#autoTime").val())){
			alert("必须填入数字！");
			return;
		}
		var time = parseInt($("#autoTime").val())*60000;
		clearInterval(window.timeToDo);
		window.timeToDo = setInterval(function(){
			time = time -1000;
			var minutes = parseInt(time / 60000);
			if(minutes < 10){
					minutes = '0' + minutes;
				}
			var tempTime = (time % 60000);
			var seconds = parseInt(tempTime / 1000);
				if(seconds < 10){
					seconds = '0' + seconds;
				}
			$("#timeLeft").text(minutes+":"+seconds);
			if(time<1){
				clearInterval(window.timeToDo);
				$(".toolbar .garybtn").click();
			}
			
		},1000);
	}
	$("#cheatConfirmTime").click(function(){
		timeOutRun();
	});
	
	
	document.onkeydown=undefined;
	
	function GetAnswerList(){
		$(".getListStatus img").show();
		$(".getListStatus span").css("color","red").html("");
		$.ajax({
			url:"http://localhost/index.php/wyndx/Index/getAnswerList",
			type:"POST",
			success:function(data){
				if(data==null){
					$("#examItem").empty().html('<option value="0">没有查到任何数据！</option>');
					$(".getListStatus img").hide();
					$(".getListStatus span").css("color","red").html("没有获取到任何数据！");
				}
				else{
					var str="";
					$.each(data,function(i){
						str += '<option value="'+data[i].examid+'" v="'+data[i].examtestid+'">'+data[i].examtitle+'---'+data[i].examauthor+'---'+data[i].examtime+'</option>';
					});
					$("#examItem").html(str);
					$(".getListStatus img").hide();
					$(".getListStatus span").css("color","green").html("获取数据成功!");
					$("#examItem").find("option").each(function(){
						if($(this).attr('v')==$("#testid").val()){
							$(this).attr("selected",true);
						}
					});
				}
			},
			error:function(){
				$(".getListStatus img").hide();
				$(".getListStatus span").css("color","red").html("获取数据失败！");
			}
		})
	}
	/*绑定select数据同步按钮*/
	$(".getListStatus a").click(function(){
		GetAnswerList();
	});
	/*开始就同步select框数据*/
	GetAnswerList();
	
	//判断页面中单选题目个数
	function SinNum(){
		var num=0;
		$(".question-option table").each(function(){
			if($(this).find("input[type='radio']").length==4){
				num+=1;
			}
		});
		return num;
	}
	//判断页面中多选题目个数
	function MulNum(){
		var num=0;
		$(".question-option table").each(function(){
			if($(this).find("input[type='checkbox']").length>=4){
				num+=1;
			}
		});
		return num;
	}
	//判断页面中判断题题目个数
	function JudNum(){
		var num=0;
		$(".question-option table").each(function(){
			if($(this).find("input[type='radio']").length==2){
				num+=1;
			}
		});
		return num;
	}
	//判断页面中填空题题目个数
	function FilNum(){
		return $(".question-content .fillblank").length;
	}
	//作弊
	$("#cheatNow").click(function(){
		var err=""; 
		var examid=$("#examItem").val();
		$.ajax({
			url:"http://localhost/index.php/wyndx/Index/getAnswer",
			type:"POST",
			data:{examid:examid},
			success:function(data){
				//题数检测
				if(data.examanswersin){
					if(data.examanswersin.length!=SinNum())
					err+="-单项选择题答案数目不匹配-";
					
				}
				if(data.examanswermul){
					if(data.examanswermul.length!=MulNum())
					err+="-多项选择题答案数目不匹配-";
				}
				if(data.examanswerjud){
					if(data.examanswerjud.length!=JudNum())
					err+="-判断题答案数目不匹配-";
				}
				if(data.examanswerfil){
					if(data.examanswerfil.length!=FilNum())
					err+="-填空题答案数目不匹配-";
				}
				if(err){
					alert(err);
					return;
				}
				//做单选题
				var inum=0;
				$(".question-option table").each(function(){
					if ($(this).find("input[type='radio']").length>=4){
						$(this).find("input[type='radio']").each(function(){
							if($(this).parent().next().children("label").text()==data.examanswersin[inum]){
								$(this).click();
							}
						});
						inum+=1;
					}
				});
				//做多选题
				var inum=0;
				$(".question-option table").each(function(){
					if ($(this).find("input[type='checkbox']").length>=4){
						$(this).find("input[type='checkbox']").each(function(){
							var reg =new RegExp($(this).parent().next().children("label").text(),"img");
							if(reg.test(data.examanswermul[inum])){
								$(this).click();
							}
						});
						inum+=1;
					}
				});
				//判断题
				var inum=0;
				$(".question-option table").each(function(){
					if ($(this).find("input[type='radio']").length==2){
						if(data.examanswerjud[inum]=="true"){
							$(this).find("input[type='radio']").eq(0).click();
						}
						else{
							$(this).find("input[type='radio']").eq(1).click();
						}
						inum+=1;
					}
					
				});
				//填空
				$(".question-content .fillblank").each(function(i){
					$(this).val(data.examanswerfil[i]);
				});
				alert("试卷已经做完！不及格别怪我。");
			}
		});
		
	});
})
