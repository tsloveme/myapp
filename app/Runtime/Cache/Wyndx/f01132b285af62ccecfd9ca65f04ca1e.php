<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- saved from url=(0119)http://wyndx.wyn88.com/testmgt/test!start.action?visitType=html&activityId=862&saveType=ACTIVITY&actAttId=258552&id=100 -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>SunLearning网络学习平台</title>
<meta http-equiv="Cache-Control" content="no-store">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta content="深圳市鑫日科科技有限公司(0755-27777522)" name="Author">
<meta content="www.xinrike.com" name="Copyright">

<script type="text/javascript">
<!--
	var ctx = "";
	var curSkin = "blue";
	var curLocale = "zh_CN";
-->
</script>
	<link rel="stylesheet" type="text/css" media="all" href="/Public/wyndx/easyui.css">
	<link rel="stylesheet" type="text/css" media="all" href="/Public/wyndx/exam.css">
	<link rel="stylesheet" type="text/css" media="all" href="/Public/wyndx/page.css">
	<link rel="stylesheet" type="text/css" media="all" href="/Public/wyndx/icon.css">
	<link rel="stylesheet" type="text/css" media="all" href="/Public/wyndx/extention.css">
	<script src="/Public/wyndx/jquery.js" type="text/javascript"></script>
	<script src="/Public/wyndx/jquery.easyui.min.js" type="text/javascript"></script>
	<script src="/Public/wyndx/utils.js" type="text/javascript"></script>
	<script src="/Public/wyndx/jquery.form.js" type="text/javascript"></script>
	<script src="/Public/wyndx/jquery.corner.js" type="text/javascript"></script>
	<script type="text/javascript">
	<!--
		var surveyind = false;
		var testing = true;
		var openModule = 'startTest';
		var displayResult = false;
		var answeredQuestion = '';
		var displayMode = 0;
		var commitMode = 3;
		var curAnsweredQueId = '';
	
		//answer question time
		var answerQuestionInteval = null;
		var answerQuestionTime = (0 > 0 ? parseInt(0) : parseInt(30)) * 60 * 1000;
		function setAnswerQuestionTime(){
			if(answerQuestionTime > 0){
				var hours = parseInt(answerQuestionTime / 3600000);
				if(hours < 10){
					hours = '0' + hours
				}
				var tempAnswerQuestionTime = (answerQuestionTime % 3600000);
				var minutes = parseInt(tempAnswerQuestionTime / 60000);
				if(minutes < 10){
					minutes = '0' + minutes;
				}
				tempAnswerQuestionTime = (tempAnswerQuestionTime % 60000);
				var seconds = parseInt(tempAnswerQuestionTime / 1000);
				if(seconds < 10){
					seconds = '0' + seconds;
				}
				$('#leavingTimes').text(hours + ':' + minutes + ':' + seconds);
			}else{
				if(answerQuestionInteval != null){
					clearInterval(answerQuestionInteval);
				}
				alert("您的作答时间已到，系统将自动提交您的试卷！");
				submitTest();
			}
			answerQuestionTime -= 1000;
		}
		
		function disableAnswer(disabled){
			$('input:radio', '#testContent').attr('disabled', disabled);
			$('input:checkbox', '#testContent').attr('disabled', disabled);
			$('input.fillblank', '#testContent').attr('disabled', disabled);
			$('textarea', '#testContent').attr('disabled', disabled);
		}
		
		function stopTesting(){
			var pauseLabel = '暂停做题';
			var startLabel = '开始做题';
			if(answerQuestionInteval === null){
				answerQuestionInteval = setInterval(setAnswerQuestionTime, 1000);
				$('#timeButton').text(pauseLabel);
				disableAnswer(false);
			}else{
				clearInterval(answerQuestionInteval);
				answerQuestionInteval = null;
				$('#timeButton').text(startLabel);
				disableAnswer(true);
			}
		}

		//set select question answer
		function setSelectQuestionAnswer(optionName, myAnswerSpanId){
			var allOptionElements = $('input[name=' + optionName + ']');
			var myAnswerText = '';
			var checkedCount = 0;
			for(var n=0; n<allOptionElements.length; n++){
				if(allOptionElements[n].checked){
					myAnswerText += (allOptionElements[n].getAttribute('alt') + ' ');
					checkedCount++;
				}
			}
			$('#' + myAnswerSpanId).text(myAnswerText);

			if(displayMode == 2){
				var questionContent = allOptionElements.closest('div.question-content');
				var queid = questionContent.attr('queid');
				var donenum = parseInt(questionContent.parent().attr('donenum'));
				var questionTypeId = questionContent.find('.question-order').attr('id');
				var answered = true;
				if(questionTypeId.indexOf('sin_que_') == 0 && checkedCount != 1){
					answered = false;
				}else if(questionTypeId.indexOf('multi_que_') == 0 && checkedCount < 2){
					answered = false;
				}else if(questionTypeId.indexOf('unmu_que_') == 0 && checkedCount < 1){
					answered = false;
				}
				if(answeredQuestion.indexOf(queid+'~') == 0 || answeredQuestion.indexOf('~'+queid+'~') > -1){
					if(!answered){
						answeredQuestion = answeredQuestion.replace(queid+'~', '');
						questionContent.parent().attr('donenum', donenum-1);
						var categoryIndex = questionContent.parent().attr('id').replace('categoryIndex', '');
						var categoryTitleElement = $('#bigQuestionNav').find('input[categoryindex='+categoryIndex+']');
						categoryTitleElement.val(categoryTitleElement.val().replace('('+donenum, '('+(donenum-1)));
					}
				}else{
					if(answered){
						answeredQuestion += queid+'~';
						questionContent.parent().attr('donenum', donenum+1);
						var categoryIndex = questionContent.parent().attr('id').replace('categoryIndex', '');
						var categoryTitleElement = $('#bigQuestionNav').find('input[categoryindex='+categoryIndex+']');
						categoryTitleElement.val(categoryTitleElement.val().replace('('+donenum, '('+(donenum+1)));
					}
				}
			}else if(displayMode == 1){
				var questionContent = allOptionElements.closest('div.question-content');
				var queid = questionContent.attr('queid');
				var donenum = parseInt($('#MyExamNum').text());
				var questionTypeId = questionContent.find('.question-order').attr('id');
				var answered = true;
				if(questionTypeId.indexOf('sin_que_') == 0 && checkedCount != 1){
					answered = false;
				}else if(questionTypeId.indexOf('multi_que_') == 0 && checkedCount < 2){
					answered = false;
				}else if(questionTypeId.indexOf('unmu_que_') == 0 && checkedCount < 1){
					answered = false;
				}
				if(answeredQuestion.indexOf(queid+'~') == 0 || answeredQuestion.indexOf('~'+queid+'~') > -1){
					if(!answered){
						answeredQuestion = answeredQuestion.replace(queid+'~', '');
						$('#MyExamNum').text(donenum-1);
						$('#ElseExamNum').text(parseInt($('#ElseExamNum').text())+1);
					}
				}else{
					if(answered){
						answeredQuestion += queid+'~';
						$('#MyExamNum').text(donenum+1);
						$('#ElseExamNum').text(parseInt($('#ElseExamNum').text())-1);
					}
				}
			}
		}
		//set jurge question answer
		function setJurgeQuestionAnswer(answer, myAnswerSpanId){
			$('#' + myAnswerSpanId).toggleClass('judge-true', answer === 'R');
			$('#' + myAnswerSpanId).toggleClass('judge-false', answer === 'W');

			if(displayMode == 2){
				var questionContent = $('#' + myAnswerSpanId).closest('div.question-content');
				var queid = questionContent.attr('queid');
				var donenum = parseInt(questionContent.parent().attr('donenum'));
				if(answeredQuestion.indexOf(queid+'~') != 0 && answeredQuestion.indexOf('~'+queid+'~') == -1){
					answeredQuestion += queid+'~';
					questionContent.parent().attr('donenum', donenum+1);
					var categoryIndex = questionContent.parent().attr('id').replace('categoryIndex', '');
					var categoryTitleElement = $('#bigQuestionNav').find('input[categoryindex='+categoryIndex+']');
					categoryTitleElement.val(categoryTitleElement.val().replace('('+donenum, '('+(donenum+1)));
				}
			}else if(displayMode == 1){
				var questionContent = $('#' + myAnswerSpanId).closest('div.question-content');
				var queid = questionContent.attr('queid');
				var donenum = parseInt($('#MyExamNum').text());
				var answered = $('#' + myAnswerSpanId).closest('tr').find('input:checked').length > 0;
				if(answeredQuestion.indexOf(queid+'~') == 0 || answeredQuestion.indexOf('~'+queid+'~') > -1){
					if(!answered){
						answeredQuestion = answeredQuestion.replace(queid+'~', '');
						$('#MyExamNum').text(donenum-1);
						$('#ElseExamNum').text(parseInt($('#ElseExamNum').text())+1);
					}
				}else{
					if(answered){
						answeredQuestion += queid+'~';
						$('#MyExamNum').text(donenum+1);
						$('#ElseExamNum').text(parseInt($('#ElseExamNum').text())-1);
					}
				}
			}
		}

		function checkTest(showMsgInd){
			var existsNotAnswered = false;
			$('a.question-order', '#testContent').each(function(i) {
				var elementId = $(this).attr('id');
				if(elementId.indexOf('sin_que_') == 0 || elementId.indexOf('jurge_que_') == 0 || elementId.indexOf('multi_que_') == 0 || elementId.indexOf('unmu_que_') == 0){
					var queName = elementId.substring(0, elementId.indexOf('_index'));
					var checkedCount = 0;
					$('input[name=' + queName + ']', '#testContent').each(function() {
						if($(this).attr('checked') == true){
							checkedCount++;
						}
					});
					var needCheckedCount = elementId.indexOf('multi_que_') == 0 ? 2 : 1;
					existsNotAnswered = checkedCount < needCheckedCount;
				}else if(elementId.indexOf('fill_que_') == 0){
					var queDiv = $(this).parent().next();
					$('input', queDiv).each(function() {
						if($.trim($(this).val()) == ''){
							existsNotAnswered = true;
							return false;
						}
					});
				}else if(elementId.indexOf('word_que_') == 0){
					var queName = elementId.substring(0, elementId.indexOf('_index'));
					existsNotAnswered = $.trim($('textarea[name=' + queName + ']', '#testContent').val()) == ''
				}
				if(existsNotAnswered){
					if(showMsgInd){
						$('#unAnswerQuestionNumberDiv').css('display', 'block');
						$('#unAnswerQuestionNumber').text('第 {0} 题未答！'.replace('{0}', $(this).text()));
						$(this).focus();
					}
					return false;
				}
			});
			if(!existsNotAnswered && showMsgInd){
				$('#unAnswerQuestionNumberDiv').css('display', 'block');
				$('#unAnswerQuestionNumber').text('题目已全部作答！');
			}
			return !existsNotAnswered;
		}
		
		function commitTest(){
			if(!checkTest(false)){
				if(confirm("您还有题目没有作答完，是否确定提交？")){
					submitTest();
				};
			}else{
				submitTest();
			}
		}

		var autoSubmitingTest = false;
		function autoSubmitTest(queid){
			if(!autoSubmitingTest){
				autoSubmitingTest = true;
				$('#autoCommit').val('true');
				if(!queid){
					if(displayMode == 1){
						queid = $('input.inputo, input.markInputo', '#questionNav').attr('queid');
					}else if(displayMode == 2){
						queid = $('input.inputo, input.markInputo', '#bigQuestionNav').attr('categoryIndex');
					}else{
						queid = '0';
					}
				}
				$('#curAnsweredQueId').val(queid);
				$('#answeredQuestion').val(answeredQuestion);
				$('#startTestForm').ajaxSubmit(function(data) {
					var returnInfo = $.parseJSON(data);
					if(returnInfo.success == true){
						$('#actatthisid').val(returnInfo.actatthisid);
						$('#startTime').val(returnInfo.startTime);
						autoSubmitingTest = false;
					}
				});
			}
		}

		function submitTest(){
			$('#autoCommit').val('false');
			showLoadingMask('container', '正在提交试卷，请稍等...');
			$('#startTestForm').ajaxSubmit(function(data) {
				var returnInfo = $.parseJSON(data);
				alert(returnInfo.msg);
				if(returnInfo.success == true){
					if('ACTIVITY' === 'QUESTIONNAIRE'){
						//window.opener.location.reload();
						//window.opener=null;
						//window.close();
					}else if('ACTIVITY' === 'ACTIVITY'){
						//window.parent.reloadAttendance();
						//window.parent.ClosePop();
					}
					window.opener.location.reload();
					window.opener=null;
					window.close();
				}else{
					window.opener.location.reload();
					window.opener=null;
					window.close();
				}
			});
		}
		
		function changePage(page){
			window.location.href = '/testmgt/test!checkAttendance.action?visitType=html&actTestAttId=0&page=' + page;
		}

		function activeSession() {
			window.setTimeout(function(){
				$.ajax({
					type: 'POST',
					url: '/orgmgt/user!activeSession.action',
					success: function(data, textStatus){
						activeSession();
					}
				});
			}, 1000*60*5);
		}

		function startMonitor(){
			window.setTimeout(function(){
				$.ajax({
					type: 'POST',
					dataType : 'json',
					url: '/onemgt/exam!startMonitor.action',
					data : {
						id : '1021'
					},
					success: function(data, textStatus){
						if(data.forcesubmit == 1){
							alert("您的考试已被监考员强制提交，感谢你的作答！");
							submitTest();
						}else{
							if(data.message){
								alert(data.message);
							}
							if(data.addtimemin > 0){
								answerQuestionTime += data.addtimemin * 60 * 1000;
							}
							startMonitor();
						}
					}
				});
			}, 1000*55);
		}

		var alt_limit = '2';
		var ctr_limit = '';
		var win_limit = '2';
		var win_pressed = 0;
       	var alt_pressed = 0;
       	var ctr_pressed = 0;
       	
       	function KeyDown(e) {
       		var keycode = 0;
       		if(navigator.appName == "Microsoft Internet Explorer")
		    {
		         　　    　   keycode = window.event.keyCode;  
		    }else
		        　{
		        　　   　　 keycode = e.which;  
		       　}

       		if (keycode==18) {
       			alt_pressed++;
       			if (alt_limit > 0 && alt_pressed > alt_limit) {
       				alert("按alt键次数已超过指定次数，试卷将马上自动提交！");
       				submitTest();
       			} else if (alt_limit > 0){
       				$("#showMessage").text("按alt键次数不能超过"+alt_limit+"次，超过"+alt_limit+"次试卷自动提交");
       			}
       		}
       		if (keycode==9) {
       			ctr_pressed++;
       			if (ctr_limit > 0 && ctr_pressed >= ctr_limit) {
       				alert("按tab键次数已超过指定次数，试卷将马上自动提交！");
       				submitTest();
       			} else if (ctr_limit > 0){
       				$("#showMessage").text("按tab键次数不能超过"+ctr_limit+"次，超过"+ctr_limit+"次试卷自动提交");
       			}
       		}
       		if (keycode==91) {
       			win_pressed++;
       			if (win_limit > 0 && win_pressed >= win_limit) {
       				alert("按window键次数已超过指定次数，试卷将马上自动提交！");
       				submitTest();
       			} else if (win_pressed > 0){
       				$("#showMessage").text("按window键次数不能超过"+win_limit+"次，超过"+win_limit+"次试卷自动提交");
       			}
       		}
       	}
       	if(testing){
        	document.onkeydown = KeyDown;
       	}
       	
       	function fullScreen() {
       		var docElm = document.documentElement;
       		if (docElm.requestFullscreen) {  
       		    docElm.requestFullscreen();  
       		}
       		//FireFox  
       		else if (docElm.mozRequestFullScreen) {  
       		    docElm.mozRequestFullScreen();  
       		}
       		//Chrome等  
       		else if (docElm.webkitRequestFullScreen) {  
       		    docElm.webkitRequestFullScreen();
       		}
       		//IE11
       		else if (elem.msRequestFullscreen) {
       		  elem.msRequestFullscreen();
       		}
      	}

       	window.onload = function() {
       		var WshShell = new ActiveXObject('WScript.Shell');
       		WshShell.SendKeys('{F11}');
       		if(testing){
				if('1021' != '0'){
					window.opener.setMonitorId('1021');
					startMonitor();
				}else{
					activeSession();
				}
			}
       	}; 
      	
		$(document).ready(function() {
			
			$('.easyui-linkbutton').linkbutton();

			//set test content style
			var testContentEle = $("#testContent");
			var contentHeight = $.browser.msie && $.browser.version < 7 ? $(window).height()-60 : $(window).height()-80;
			if(testContentEle.height() < contentHeight && $.browser.msie && $.browser.version == "6.0"){
				contentHeight -= 15;
			}
			testContentEle.panel({
				"border": false,
				"height": contentHeight
			});
			$(window).resize(function() {
				testContentEle.panel({
					"height": contentHeight
				});
			});
			if($.browser.msie && $.browser.version < 7){
				testContentEle.children('div').width(testContentEle.width()-20);
			}
			if($.browser.mozilla){
				testContentEle[0].addEventListener('DOMMouseScroll', function(e){
					testContentEle[0].scrollTop += e.detail > 0 ? 60 : -60;
			        e.preventDefault();
			    }, false);
			}else if($.browser.msie){   
				testContentEle[0].onmousewheel = function(e){
			        e = e || window.event;
			        testContentEle[0].scrollTop += e.wheelDelta > 0 ? -60 : 60;
			        e.returnValue = false;
			    };
			}
			
			//set answer question time
			if(!surveyind && testing && answerQuestionTime > 0){
				answerQuestionInteval = setInterval(setAnswerQuestionTime, 1000);
			}
			if(testing){
				fullScreen();
			}
			//auto commit test every five minutes
			if(commitMode == 3 && '5'){
				setInterval(autoSubmitTest, 5*60*1000);
			}
			//hidden 'unAnswerQuestionNumberDiv' div
			$('#testContent').click(function() {
				$('#unAnswerQuestionNumberDiv').css('display', 'none');
			});
			//disable question answer textArea
			if(!testing){
				disableAnswer(true);
			}
			//clear fillBlank question space value
			if(openModule === 'attendance' && !displayResult){
				$('input.fillblank', '#testContent').val('');
			}
			
			//disabled right click
			$(document.body).bind("selectstart", function(){
				return false;
			});
			$(document).bind("contextmenu",function(e){
				return false;
			});
			
			$('#container').corner("dog 30px tl");

			if(displayMode == 1 || displayMode == 2){
				var old_fade_in = $.fn.fadeIn;
				var old_fade_out = $.fn.fadeOut;
				$.fn.fadeIn = function(){
					if ($.browser.msie && $.browser.version < 8.0 && !$.support.style) {
						$(this).show();
						if (typeof arguments[arguments.length-1] === 'function'){
							arguments[arguments.length-1]();
						};
					}else{
						old_fade_in.call(this, arguments[0], arguments[1], arguments[2]);
					};
					return this;
				};
				$.fn.fadeOut = function(){
					if ($.browser.msie && $.browser.version < 8.0 && !$.support.style) {
						$(this).hide();
						if (typeof arguments[arguments.length-1] === 'function'){
							arguments[arguments.length-1]();
						};
					}else{
						old_fade_out.call(this, arguments[0], arguments[1], arguments[2]);
					};
					return this;
				};
			}
			if(displayMode == 2){
				$('input', '#bigQuestionNav').click(function(){
					var curCatagoryNumber = $('#bigQuestionNav').attr('curCatagoryNumber');
					var newBigQuestion = $(this);
					if(!newBigQuestion.hasClass('inputo')){
						var newCatagoryNumber = newBigQuestion.attr('categoryIndex');
						$('#categoryIndex'+curCatagoryNumber).fadeOut("fast", function() {
							$('#bigQuestionNav').attr('curCatagoryNumber', newCatagoryNumber);
							$('input.inputo', '#bigQuestionNav').removeClass('inputo').addClass('input');
							newBigQuestion.removeClass('input').addClass('inputo');
							$('#categoryIndex'+newCatagoryNumber).fadeIn("fast");
							if(newBigQuestion.prev().length == 0 && $('#PreTab', '#contentFooterNav').hasClass('prev-btn')){
								$('#PreTab', '#contentFooterNav').removeClass('prev-btn').addClass('prev-btn2');
							}else if(newBigQuestion.prev().length > 0 && $('#PreTab', '#contentFooterNav').hasClass('prev-btn2')){
								$('#PreTab', '#contentFooterNav').removeClass('prev-btn2').addClass('prev-btn');
							}
							if(newBigQuestion.next().length == 0 && $('#NextTab', '#contentFooterNav').hasClass('next-btn')){
								$('#NextTab', '#contentFooterNav').removeClass('next-btn').addClass('next-btn2');
							}else if(newBigQuestion.next().length > 0 && $('#NextTab', '#contentFooterNav').hasClass('next-btn2')){
								$('#NextTab', '#contentFooterNav').removeClass('next-btn2').addClass('next-btn');
							}
						});
						if(commitMode == 2){
							autoSubmitTest(newCatagoryNumber);
						}
					}
				});
				$('textarea,.fillblank', '.question-content').blur(function(){
					var questionContent = $(this).closest('div.question-content');
					var queid = questionContent.attr('queid');
					var donenum = parseInt(questionContent.parent().attr('donenum'));
					var answered = $.trim($(this).val()).length > 0;
					if(answered && $(this).hasClass('fillblank')){
						questionContent.find('.fillblank').each(function() {
							if($.trim($(this).val()).length == 0){
								answered = false;
								return false;
							}
						});
					}
					if(answeredQuestion.indexOf(queid+'~') == 0 || answeredQuestion.indexOf('~'+queid+'~') > -1){
						if(!answered){
							answeredQuestion = answeredQuestion.replace(queid+'~', '');
							questionContent.parent().attr('donenum', donenum-1);
							var categoryIndex = questionContent.parent().attr('id').replace('categoryIndex', '');
							var categoryTitleElement = $('#bigQuestionNav').find('input[categoryindex='+categoryIndex+']');
							categoryTitleElement.val(categoryTitleElement.val().replace('('+donenum, '('+(donenum-1)));
						}
					}else{
						if(answered){
							answeredQuestion += queid+'~';
							questionContent.parent().attr('donenum', donenum+1);
							var categoryIndex = questionContent.parent().attr('id').replace('categoryIndex', '');
							var categoryTitleElement = $('#bigQuestionNav').find('input[categoryindex='+categoryIndex+']');
							categoryTitleElement.val(categoryTitleElement.val().replace('('+donenum, '('+(donenum+1)));
						}
					}
				});
				
				$('#PreTab', '#contentFooterNav').click(function() {
					var curCatagoryNumber = parseInt($('#bigQuestionNav').attr('curCatagoryNumber'));
					if(curCatagoryNumber > 1){
						$('#categoryIndex'+curCatagoryNumber).fadeOut("fast", function() {
							$('#bigQuestionNav').attr('curCatagoryNumber', curCatagoryNumber-1);
							$('input.inputo', '#bigQuestionNav').removeClass('inputo').addClass('input').prev().removeClass('input').addClass('inputo');
							$('#categoryIndex'+(curCatagoryNumber-1)).fadeIn("fast");
							if($('input.inputo', '#bigQuestionNav').prev().length == 0){
								$('#PreTab', '#contentFooterNav').removeClass('prev-btn').addClass('prev-btn2');
							}
							if($('#NextTab', '#contentFooterNav').hasClass('next-btn2')){
								$('#NextTab', '#contentFooterNav').removeClass('next-btn2').addClass('next-btn');
							}
							if(commitMode == 2){
								autoSubmitTest(curCatagoryNumber-1);
							}
						});
					}
				});
				$('#NextTab', '#contentFooterNav').click(function() {
					var curCatagory = $('input.inputo', '#bigQuestionNav');
					if(curCatagory.next().length > 0){
						var curCatagoryNumber = parseInt(curCatagory.attr('categoryindex'));
						$('#categoryIndex'+curCatagoryNumber).fadeOut("fast", function() {
							$('#bigQuestionNav').attr('curCatagoryNumber', curCatagoryNumber+1);
							curCatagory.removeClass('inputo').addClass('input').next().removeClass('input').addClass('inputo');
							$('#categoryIndex'+(curCatagoryNumber+1)).fadeIn("fast");
							if($('input.inputo', '#bigQuestionNav').next().length == 0){
								$('#NextTab', '#contentFooterNav').removeClass('next-btn').addClass('next-btn2');
							}
							if($('#PreTab', '#contentFooterNav').hasClass('prev-btn2')){
								$('#PreTab', '#contentFooterNav').removeClass('prev-btn2').addClass('prev-btn');
							}
							if(commitMode == 2){
								autoSubmitTest(curCatagoryNumber+1);
							}
						});
					}
				});
				
			}else if(displayMode == 1){
				if ($.browser.msie && $.browser.version < 8.0) {
					$('.question-title').css('border', '1px #fff solid');
				}
				var firstQueid = $('#testContent').find('.question-content:first').attr('queid');
				var lastQueid = $('#testContent').find('.question-content:last').attr('queid');
				var onQuestionChange = false;
				$('#PreTab', '#contentFooterNav').click(function() {
					if(onQuestionChange || $(this).hasClass('prev-btn2')) return;
					var curQuestion = $('.question-content:visible');
					var newQuestion = curQuestion.prev();
					if(newQuestion.length > 0 && newQuestion.hasClass('question-content')){
						onQuestionChange = true;
						curQuestion.fadeOut("fast", function() {
							var prevInput;
							if($('input.markInputo', '#questionNav').length > 0){
								prevInput = $('input.markInputo', '#questionNav').removeClass('markInputo').addClass('markInput').prev();
							}else{
								prevInput = $('input.inputo', '#questionNav').removeClass('inputo').addClass('input').prev();
							}
							if(prevInput.is(':hidden')){
								showPrevioursQuestion();
							}
							if(prevInput.hasClass('markInput')){
								prevInput.removeClass('markInput').addClass('markInputo');
							}else{
								prevInput.removeClass('input').addClass('inputo');
							}
							
							newQuestion.fadeIn("fast", function(){
								onQuestionChange = false;
								newQuestion.find('.question-order').focus();
							});
						});
					}else if(curQuestion.parent().prev().length > 0){
						newQuestion = curQuestion.parent().prev().find('div:last-child');
						onQuestionChange = true;
						curQuestion.fadeOut("fast", function() {
							var prevInput;
							if($('input.markInputo', '#questionNav').length > 0){
								prevInput = $('input.markInputo', '#questionNav').removeClass('markInputo').addClass('markInput').prev();
							}else{
								prevInput = $('input.inputo', '#questionNav').removeClass('inputo').addClass('input').prev();
							}
							if(prevInput.is(':hidden')){
								showPrevioursQuestion();
							}
							if(prevInput.hasClass('markInput')){
								prevInput.removeClass('markInput').addClass('markInputo');
							}else{
								prevInput.removeClass('input').addClass('inputo');
							}
							newQuestion.fadeIn("fast", function(){
								onQuestionChange = false;
								newQuestion.find('.question-order').focus();
							});
						});
					}
					if(commitMode == 1){
						autoSubmitTest(newQuestion.attr('queid'));
					}
					if(newQuestion.attr('queid') == firstQueid){
						$('#PreTab', '#contentFooterNav').removeClass('prev-btn').addClass('prev-btn2');
					}
					if($('#NextTab', '#contentFooterNav').hasClass('next-btn2')){
						$('#NextTab', '#contentFooterNav').removeClass('next-btn2').addClass('next-btn');
					}
				});
				$('#NextTab', '#contentFooterNav').click(function() {
					if(onQuestionChange || $(this).hasClass('next-btn2')) return;
					var curQuestion = $('.question-content:visible');
					var newQuestion = curQuestion.next();
					if(newQuestion.length > 0){
						onQuestionChange = true;
						curQuestion.fadeOut("fast", function() {
							var nextInput;
							if($('input.markInputo', '#questionNav').length > 0){
								nextInput = $('input.markInputo', '#questionNav').removeClass('markInputo').addClass('markInput').next();
							}else{
								nextInput = $('input.inputo', '#questionNav').removeClass('inputo').addClass('input').next();
							}
							if(nextInput.is(':hidden')){
								showNextQuestion();
							}
							if(nextInput.hasClass('markInput')){
								nextInput.removeClass('markInput').addClass('markInputo');
							}else{
								nextInput.removeClass('input').addClass('inputo');
							}
							newQuestion.fadeIn("fast", function(){
								onQuestionChange = false;
								newQuestion.find('.question-order').focus();
							});
						});
					}else if(curQuestion.parent().next().length > 0){
						newQuestion = curQuestion.parent().next().find('div:nth-child(2)');
						onQuestionChange = true;
						curQuestion.fadeOut("fast", function() {
							var nextInput;
							if($('input.markInputo', '#questionNav').length > 0){
								nextInput = $('input.markInputo', '#questionNav').removeClass('markInputo').addClass('markInput').next();
							}else{
								nextInput = $('input.inputo', '#questionNav').removeClass('inputo').addClass('input').next();
							}
							if(nextInput.is(':hidden')){
								showNextQuestion();
							}
							if(nextInput.hasClass('markInput')){
								nextInput.removeClass('markInput').addClass('markInputo');
							}else{
								nextInput.removeClass('input').addClass('inputo');
							}
							newQuestion.fadeIn("fast", function(){
								onQuestionChange = false;
								newQuestion.find('.question-order').focus();
							});
						});
					}
					if(commitMode == 1){
						autoSubmitTest(newQuestion.attr('queid'));
					}
					if(newQuestion.attr('queid') == lastQueid){
						$('#NextTab', '#contentFooterNav').removeClass('next-btn').addClass('next-btn2');
					}
					if($('#PreTab', '#contentFooterNav').hasClass('prev-btn2')){
						$('#PreTab', '#contentFooterNav').removeClass('prev-btn2').addClass('prev-btn');
					}
				});

				$('input', '#questionNav').click(function(){
					if(onQuestionChange) return;
					if($(this).hasClass('previous')){
						if(!$(this).hasClass('notprevious')){
							onQuestionChange = true;
							showPrevioursQuestion(true);
						}
					}else if($(this).hasClass('next')){
						if(!$(this).hasClass('notnext')){
							showNextQuestion(true);
						}
					}else{
						var curQuestion = $('.question-content:visible');
						var queid = $(this).attr('queid');
						var newQuestion = $('.question-content[queid='+queid+']');
						onQuestionChange = true;
						curQuestion.fadeOut("fast", function() {
							if($('input.markInputo', '#questionNav').length > 0){
								$('input.markInputo', '#questionNav').removeClass('markInputo').addClass('markInput');
							}else{
								$('input.inputo', '#questionNav').removeClass('inputo').addClass('input');
							}
							if($('input[queid='+queid+']', '#questionNav').hasClass('markInput')){
								$('input[queid='+queid+']', '#questionNav').removeClass('markInput').addClass('markInputo');
							}else{
								$('input[queid='+queid+']', '#questionNav').removeClass('input').addClass('inputo');
							}
							newQuestion.fadeIn("fast", function(){
								onQuestionChange = false;
								newQuestion.find('.question-order').focus();
							});
						});
						if(commitMode == 1){
							autoSubmitTest(queid);
						}
						if(queid == firstQueid){
							if($('#PreTab', '#contentFooterNav').hasClass('prev-btn')){
								$('#PreTab', '#contentFooterNav').removeClass('prev-btn').addClass('prev-btn2');
							}
							if($('#NextTab', '#contentFooterNav').hasClass('next-btn2')){
								$('#NextTab', '#contentFooterNav').removeClass('next-btn2').addClass('next-btn');
							}
						}else if(queid == lastQueid){
							if($('#PreTab', '#contentFooterNav').hasClass('prev-btn2')){
								$('#PreTab', '#contentFooterNav').removeClass('prev-btn2').addClass('prev-btn');
							}
							if($('#NextTab', '#contentFooterNav').hasClass('next-btn')){
								$('#NextTab', '#contentFooterNav').removeClass('next-btn').addClass('next-btn2');
							}
						}else{
							if($('#PreTab', '#contentFooterNav').hasClass('prev-btn2')){
								$('#PreTab', '#contentFooterNav').removeClass('prev-btn2').addClass('prev-btn');
							}
							if($('#NextTab', '#contentFooterNav').hasClass('next-btn2')){
								$('#NextTab', '#contentFooterNav').removeClass('next-btn2').addClass('next-btn');
							}
						}
					}
				});

				var showPrevioursQuestion = function (changeStatus) {
					var prevQuestion = $('input[queid]:visible:first', '#questionNav').prevAll(':lt(10)');
					$('input[queid]:visible', '#questionNav').fadeOut("fast", function() {
						prevQuestion.fadeIn("fast", function(){
							if(changeStatus){
								onQuestionChange = false;
							}
						});
						if($('input.next', '#questionNav').hasClass('notnext')){
							$('input.next', '#questionNav').removeClass('notnext');
						}
						if(prevQuestion.filter(':last').prevAll().length <= 1){
							$('input.previous', '#questionNav').addClass('notprevious');
						}
					});
				};

				var showNextQuestion = function (changeStatus) {
					var nextQuestion = $('input[queid]:visible:last', '#questionNav').nextAll(':lt(10)');
					$('input[queid]:visible', '#questionNav').fadeOut("fast", function() {
						nextQuestion.fadeIn("fast", function(){
							if(changeStatus){
								onQuestionChange = false;
							}
						});
						if($('input.previous', '#questionNav').hasClass('notprevious')){
							$('input.previous', '#questionNav').removeClass('notprevious');
						}
						if(nextQuestion.filter(':last').nextAll().length <= 1){
							$('input.next', '#questionNav').addClass('notnext');
						}
					});
				};

				$('textarea,.fillblank', '.question-content').blur(function(){
					var questionContent = $(this).closest('div.question-content');
					var queid = questionContent.attr('queid');
					var donenum = parseInt($('#MyExamNum').text());
					var answered = $.trim($(this).val()).length > 0;
					if(answered && $(this).hasClass('fillblank')){
						questionContent.find('.fillblank').each(function() {
							if($.trim($(this).val()).length == 0){
								answered = false;
								return false;
							}
						});
					}
					if(answeredQuestion.indexOf(queid+'~') == 0 || answeredQuestion.indexOf('~'+queid+'~') > -1){
						if(!answered){
							answeredQuestion = answeredQuestion.replace(queid+'~', '');
							$('#MyExamNum').text(donenum-1);
							$('#ElseExamNum').text(parseInt($('#ElseExamNum').text())+1);
						}
					}else{
						if(answered){
							answeredQuestion += queid+'~';
							$('#MyExamNum').text(donenum+1);
							$('#ElseExamNum').text(parseInt($('#ElseExamNum').text())-1);
						}
					}
				});
				$('a', '.markQuestion').toggle(
					function () {
						$(this).removeClass("unsure");
						$(this).text('[取消标记]');
						var queid = $(this).closest('div.question-content').attr('queid');
						var queBtn = $('input[queid='+queid+']', '#questionNav');
						if(queBtn.hasClass('inputo')){
							queBtn.removeClass('inputo').addClass('markInputo');
						}else{
							queBtn.removeClass('input').addClass('markInput');
						}
					}, 
					function () {
						$(this).addClass("unsure");
						$(this).text('[暂不确定答案]');
						var queid = $(this).closest('div.question-content').attr('queid');
						var queBtn = $('input[queid='+queid+']', '#questionNav');
						if(queBtn.hasClass('markInputo')){
							queBtn.removeClass('markInputo').addClass('inputo');
						}else{
							queBtn.removeClass('markInput').addClass('input');
						}
					} 
				);
				$('a', '.selectOneAnswer, .selectMutiAnswer').click(function () {
					if($(this).hasClass('u_buttoned')){
						$(this).removeClass('u_buttoned');
						$('#'+$(this).attr('optionId')).attr('checked', false);
					}else{
						if($(this).parent().hasClass('selectOneAnswer')){
							$(this).parent().children('.u_buttoned').removeClass('u_buttoned');
						}
						$(this).addClass('u_buttoned');
						$('#'+$(this).attr('optionId')).attr('checked', true);
					}
					if($(this).attr('optionvalue')){
						setJurgeQuestionAnswer($(this).attr('optionvalue'), $(this).attr('myAnswerSpanId'));
					}else{
						setSelectQuestionAnswer($(this).attr('optionName'), $(this).attr('myAnswerSpanId'));
					}
				});
				
				
			}
		});
		
		function hiddenPrompt(){
			$('#unAnswerQuestionNumberDiv').css('display', 'none');
		}
		

		
		var UnloadConfirm = {};
		UnloadConfirm.set = function() {
		    window.onbeforeunload = function() {
		        return window.opener.closeExamWindow();
		    }
		};
		UnloadConfirm.set();
	-->
	</script>
</head>
<body>
	<div id="container" style="zoom: 1; border: none;"><div class="jquery-corner" style="position: relative; margin: -10px -10px -20px;"><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 28px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 26px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 24px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 22px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 20px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 18px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 16px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 14px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 12px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 10px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 8px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 6px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 4px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 2px; background-color: transparent;"></div><div style="overflow: hidden; height: 1px; min-height: 1px; font-size: 1px; border-style: none none none solid; border-color: rgb(144, 153, 174); border-width: 0px 0px 0px 30px; background-color: transparent;"></div></div>
	<p style="color: red;font-size: 18px;text-align: center;" id="showMessage"></p>
	
		
			<form id="startTestForm" name="startTestForm" action="http://wyndx.wyn88.com/testmgt/testanswer!save.action" method="post" style="margin:0; padding:0;">
				<input type="hidden" id="testid" name="testid" value="100">
				<input type="hidden" id="saveType" name="saveType" value="ACTIVITY">
				<input type="hidden" id="questionnaireId" name="questionnaireId" value="0">
				<input type="hidden" id="generatemode" name="generatemode" value="1">
				<input type="hidden" id="actAttId" name="actAttId" value="258552">
				<input type="hidden" id="startTime" name="startTime" value="1423324528">
				<input type="hidden" id="categoryIdLst" name="categoryIdLst" value="103~102~">
				<input type="hidden" id="actatthisid" name="actatthisid" value="0">
				<input type="hidden" id="monitorid" name="monitorid" value="1021">
				<input type="hidden" id="autoCommit" name="autoCommit" value="false">
				<input type="hidden" id="curAnsweredQueId" name="curAnsweredQueId" value="">
				<input type="hidden" id="answeredQuestion" name="answeredQuestion" value="">

				
					
						
						
						
							<input type="hidden" name="unmuSelectInput" value="1127~1128~1129~1130~1131~1132~1133~">
						
						
						
						
					
				
					
						
						
						
						
						
							<input type="hidden" name="jurgeInput" value="1113~1114~1115~1116~1117~1118~1119~1120~1121~1122~1123~1124~1125~">
						
						
					
				

				<div class="panel" style="display: block; width: auto;"><div id="testContent" onclick="hiddenPrompt()" style="overflow-x: hidden; overflow-y: auto; width: 750px; height: 750px;" title="" class="panel-body panel-body-noheader panel-body-noborder">
					<div id="title">时间管理制度考试</div>

					
						<fieldset>
							<legend>试卷信息</legend>
							<table cellspacing="0" cellpadding="0" border="0">
								<tbody><tr>
									<td>卷面总分：<strong>100</strong> 分</td>
									<td>合格分数线：<strong>80</strong> 分</td>
									<td>答题时间：<strong>30</strong> 分钟</td>
								</tr>
								<tr>
									<td>出卷人：袁志鹏</td>
									<td colspan="2">出卷时间：2015-01-07 17:58</td>
								</tr>
							</tbody></table>
						</fieldset>
						
					

					

					
					
					<div id="split">&nbsp;</div>
					
						<div id="categoryIndex1" donenum="0" allnum="7">
						<div id="categoryNumber1" class="category-title">
							一、不定项选择题
							
						</div>
						
							
							
							
								
									
									<div id="questionNumber1" class="question-content" queid="1127">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="unmu_que_1127_index" href="javascript:void(0);">1</a>.
											</div>
											<div style="float: left; width: 94%;">
												因忘打卡或其他原因导致指纹不能打卡的，员工本人应如何处理
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2714" name="unmu_que_1127" value="2714" alt="A" onclick="setSelectQuestionAnswer(&#39;unmu_que_1127&#39;, &#39;my_unmu_que_asw_1127&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2714">A</label></td>
														<td width="95%" align="left" valign="middle">在KM中提交未打卡的流程说明</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2715" name="unmu_que_1127" value="2715" alt="B" onclick="setSelectQuestionAnswer(&#39;unmu_que_1127&#39;, &#39;my_unmu_que_asw_1127&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2715">B</label></td>
														<td width="95%" align="left" valign="middle">向直接领导口头说明</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2716" name="unmu_que_1127" value="2716" alt="C" onclick="setSelectQuestionAnswer(&#39;unmu_que_1127&#39;, &#39;my_unmu_que_asw_1127&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2716">C</label></td>
														<td width="95%" align="left" valign="middle">向中心领导书面说明</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2717" name="unmu_que_1127" value="2717" alt="D" onclick="setSelectQuestionAnswer(&#39;unmu_que_1127&#39;, &#39;my_unmu_que_asw_1127&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2717">D</label></td>
														<td width="95%" align="left" valign="middle">向HR考勤负责人写书面说明</td>
													</tr>
												
												
													<tr>
														<td align="left" colspan="3" class="myAnswer">
															<div style="float:left;" class="selectMutiAnswer">
																
																	
																	我的答案：<label class="answer-label" id="myanswer"><span id="my_unmu_que_asw_1127"></span></label>
																
															</div>
															
														</td>
													</tr>
												
											</tbody></table>
											
										</div>
									</div>
								
									
									<div id="questionNumber2" class="question-content" queid="1128">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="unmu_que_1128_index" href="javascript:void(0);">2</a>.
											</div>
											<div style="float: left; width: 94%;">
												旷工者除当日工资不发放外，每旷工1天扣除当日工资的
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2718" name="unmu_que_1128" value="2718" alt="A" onclick="setSelectQuestionAnswer(&#39;unmu_que_1128&#39;, &#39;my_unmu_que_asw_1128&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2718">A</label></td>
														<td width="95%" align="left" valign="middle">1倍</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2719" name="unmu_que_1128" value="2719" alt="B" onclick="setSelectQuestionAnswer(&#39;unmu_que_1128&#39;, &#39;my_unmu_que_asw_1128&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2719">B</label></td>
														<td width="95%" align="left" valign="middle">2倍</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2720" name="unmu_que_1128" value="2720" alt="C" onclick="setSelectQuestionAnswer(&#39;unmu_que_1128&#39;, &#39;my_unmu_que_asw_1128&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2720">C</label></td>
														<td width="95%" align="left" valign="middle">3倍</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2721" name="unmu_que_1128" value="2721" alt="D" onclick="setSelectQuestionAnswer(&#39;unmu_que_1128&#39;, &#39;my_unmu_que_asw_1128&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2721">D</label></td>
														<td width="95%" align="left" valign="middle">4倍</td>
													</tr>
												
												
													<tr>
														<td align="left" colspan="3" class="myAnswer">
															<div style="float:left;" class="selectMutiAnswer">
																
																	
																	我的答案：<label class="answer-label" id="myanswer"><span id="my_unmu_que_asw_1128"></span></label>
																
															</div>
															
														</td>
													</tr>
												
											</tbody></table>
											
										</div>
									</div>
								
									
									<div id="questionNumber3" class="question-content" queid="1129">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="unmu_que_1129_index" href="javascript:void(0);">3</a>.
											</div>
											<div style="float: left; width: 94%;">
												若员工生日假当天是休息日（含法定节假日）或因工作等特殊原因无法休假的，可提前向部门负责人申请，在生日假生效后的多少天内调休，逾期不予享受。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2722" name="unmu_que_1129" value="2722" alt="A" onclick="setSelectQuestionAnswer(&#39;unmu_que_1129&#39;, &#39;my_unmu_que_asw_1129&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2722">A</label></td>
														<td width="95%" align="left" valign="middle">10天</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2723" name="unmu_que_1129" value="2723" alt="B" onclick="setSelectQuestionAnswer(&#39;unmu_que_1129&#39;, &#39;my_unmu_que_asw_1129&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2723">B</label></td>
														<td width="95%" align="left" valign="middle">20天</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2724" name="unmu_que_1129" value="2724" alt="C" onclick="setSelectQuestionAnswer(&#39;unmu_que_1129&#39;, &#39;my_unmu_que_asw_1129&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2724">C</label></td>
														<td width="95%" align="left" valign="middle">30天</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2725" name="unmu_que_1129" value="2725" alt="D" onclick="setSelectQuestionAnswer(&#39;unmu_que_1129&#39;, &#39;my_unmu_que_asw_1129&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2725">D</label></td>
														<td width="95%" align="left" valign="middle">40天</td>
													</tr>
												
												
													<tr>
														<td align="left" colspan="3" class="myAnswer">
															<div style="float:left;" class="selectMutiAnswer">
																
																	
																	我的答案：<label class="answer-label" id="myanswer"><span id="my_unmu_que_asw_1129"></span></label>
																
															</div>
															
														</td>
													</tr>
												
											</tbody></table>
											
										</div>
									</div>
								
									
									<div id="questionNumber4" class="question-content" queid="1130">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="unmu_que_1130_index" href="javascript:void(0);">4</a>.
											</div>
											<div style="float: left; width: 94%;">
												员工年度的出勤记录可作用于哪些员工管理环节
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2726" name="unmu_que_1130" value="2726" alt="A" onclick="setSelectQuestionAnswer(&#39;unmu_que_1130&#39;, &#39;my_unmu_que_asw_1130&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2726">A</label></td>
														<td width="95%" align="left" valign="middle">下一年度可休年假多少的依据</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2727" name="unmu_que_1130" value="2727" alt="B" onclick="setSelectQuestionAnswer(&#39;unmu_que_1130&#39;, &#39;my_unmu_que_asw_1130&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2727">B</label></td>
														<td width="95%" align="left" valign="middle">调薪及年终奖金</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2728" name="unmu_que_1130" value="2728" alt="C" onclick="setSelectQuestionAnswer(&#39;unmu_que_1130&#39;, &#39;my_unmu_que_asw_1130&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2728">C</label></td>
														<td width="95%" align="left" valign="middle">年度绩效考评</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2729" name="unmu_que_1130" value="2729" alt="D" onclick="setSelectQuestionAnswer(&#39;unmu_que_1130&#39;, &#39;my_unmu_que_asw_1130&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2729">D</label></td>
														<td width="95%" align="left" valign="middle">活动经费申请的依据</td>
													</tr>
												
												
													<tr>
														<td align="left" colspan="3" class="myAnswer">
															<div style="float:left;" class="selectMutiAnswer">
																
																	
																	我的答案：<label class="answer-label" id="myanswer"><span id="my_unmu_que_asw_1130"></span></label>
																
															</div>
															
														</td>
													</tr>
												
											</tbody></table>
											
										</div>
									</div>
								
									
									<div id="questionNumber5" class="question-content" queid="1131">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="unmu_que_1131_index" href="javascript:void(0);">5</a>.
											</div>
											<div style="float: left; width: 94%;">
												公司正式员工享有带薪的假期有哪些
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2730" name="unmu_que_1131" value="2730" alt="A" onclick="setSelectQuestionAnswer(&#39;unmu_que_1131&#39;, &#39;my_unmu_que_asw_1131&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2730">A</label></td>
														<td width="95%" align="left" valign="middle">事假</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2731" name="unmu_que_1131" value="2731" alt="B" onclick="setSelectQuestionAnswer(&#39;unmu_que_1131&#39;, &#39;my_unmu_que_asw_1131&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2731">B</label></td>
														<td width="95%" align="left" valign="middle">婚假</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2732" name="unmu_que_1131" value="2732" alt="C" onclick="setSelectQuestionAnswer(&#39;unmu_que_1131&#39;, &#39;my_unmu_que_asw_1131&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2732">C</label></td>
														<td width="95%" align="left" valign="middle">生日假</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2733" name="unmu_que_1131" value="2733" alt="D" onclick="setSelectQuestionAnswer(&#39;unmu_que_1131&#39;, &#39;my_unmu_que_asw_1131&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2733">D</label></td>
														<td width="95%" align="left" valign="middle">工伤假</td>
													</tr>
												
												
													<tr>
														<td align="left" colspan="3" class="myAnswer">
															<div style="float:left;" class="selectMutiAnswer">
																
																	
																	我的答案：<label class="answer-label" id="myanswer"><span id="my_unmu_que_asw_1131"></span></label>
																
															</div>
															
														</td>
													</tr>
												
											</tbody></table>
											
										</div>
									</div>
								
									
									<div id="questionNumber6" class="question-content" queid="1132">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="unmu_que_1132_index" href="javascript:void(0);">6</a>.
											</div>
											<div style="float: left; width: 94%;">
												申请病假应递交的纸质证明文件有哪些
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2734" name="unmu_que_1132" value="2734" alt="A" onclick="setSelectQuestionAnswer(&#39;unmu_que_1132&#39;, &#39;my_unmu_que_asw_1132&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2734">A</label></td>
														<td width="95%" align="left" valign="middle">由医疗单位出具的病假单（疾病诊断书）</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2735" name="unmu_que_1132" value="2735" alt="B" onclick="setSelectQuestionAnswer(&#39;unmu_que_1132&#39;, &#39;my_unmu_que_asw_1132&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2735">B</label></td>
														<td width="95%" align="left" valign="middle">病历本出诊记录页的复印件</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2736" name="unmu_que_1132" value="2736" alt="C" onclick="setSelectQuestionAnswer(&#39;unmu_que_1132&#39;, &#39;my_unmu_que_asw_1132&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2736">C</label></td>
														<td width="95%" align="left" valign="middle">病假期间医疗单位当日的收费单据</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2737" name="unmu_que_1132" value="2737" alt="D" onclick="setSelectQuestionAnswer(&#39;unmu_que_1132&#39;, &#39;my_unmu_que_asw_1132&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2737">D</label></td>
														<td width="95%" align="left" valign="middle">由居委会或当地派出所出具的亲属关系证明复印件</td>
													</tr>
												
												
													<tr>
														<td align="left" colspan="3" class="myAnswer">
															<div style="float:left;" class="selectMutiAnswer">
																
																	
																	我的答案：<label class="answer-label" id="myanswer"><span id="my_unmu_que_asw_1132"></span></label>
																
															</div>
															
														</td>
													</tr>
												
											</tbody></table>
											
										</div>
									</div>
								
									
									<div id="questionNumber7" class="question-content" queid="1133">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="unmu_que_1133_index" href="javascript:void(0);">7</a>.
											</div>
											<div style="float: left; width: 94%;">
												关于哺乳假，下面说法不正确的是
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2738" name="unmu_que_1133" value="2738" alt="A" onclick="setSelectQuestionAnswer(&#39;unmu_que_1133&#39;, &#39;my_unmu_que_asw_1133&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2738">A</label></td>
														<td width="95%" align="left" valign="middle">符合条件的女员工每天享有两次哺乳时间，每次30分钟，也可合并60分钟一起使用</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2739" name="unmu_que_1133" value="2739" alt="B" onclick="setSelectQuestionAnswer(&#39;unmu_que_1133&#39;, &#39;my_unmu_que_asw_1133&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2739">B</label></td>
														<td width="95%" align="left" valign="middle">多胞胎生育的，每多哺乳1名婴儿，每次哺乳时间增加30分钟，即每天增加1小时。</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2740" name="unmu_que_1133" value="2740" alt="C" onclick="setSelectQuestionAnswer(&#39;unmu_que_1133&#39;, &#39;my_unmu_que_asw_1133&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2740">C</label></td>
														<td width="95%" align="left" valign="middle">女员工申请哺乳假，需按月申请或一次性全部申请完成，申请后不得变更哺乳假的休假方式。</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="checkbox" id="unmu_que_option_2741" name="unmu_que_1133" value="2741" alt="D" onclick="setSelectQuestionAnswer(&#39;unmu_que_1133&#39;, &#39;my_unmu_que_asw_1133&#39;)"></td>
														<td width="20" align="center"><label for="unmu_que_option_2741">D</label></td>
														<td width="95%" align="left" valign="middle">员工当天应休但未休的哺乳假，可以累加与调休。</td>
													</tr>
												
												
													<tr>
														<td align="left" colspan="3" class="myAnswer">
															<div style="float:left;" class="selectMutiAnswer">
																
																	
																	我的答案：<label class="answer-label" id="myanswer"><span id="my_unmu_que_asw_1133"></span></label>
																
															</div>
															
														</td>
													</tr>
												
											</tbody></table>
											
										</div>
									</div>
								
							
							
							
							
						
						</div>
						
					
						<div id="categoryIndex2" donenum="0" allnum="13">
						<div id="categoryNumber2" class="category-title">
							二、判断题
							
						</div>
						
							
							
							
							
							
								
									
									<div id="questionNumber8" class="question-content" queid="1113">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1113_index" href="javascript:void(0);">8</a>.
											</div>
											<div style="float: left; width: 94%;">
												员工在离职通知期内不可申请事假。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1113" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1113_R" name="jurge_que_1113" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1113&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1113_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1113_W" name="jurge_que_1113" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1113&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1113_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber9" class="question-content" queid="1114">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1114_index" href="javascript:void(0);">9</a>.
											</div>
											<div style="float: left; width: 94%;">
												所有转正后的正式员工，每年可享有2天的全薪病假
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1114" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1114_R" name="jurge_que_1114" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1114&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1114_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1114_W" name="jurge_que_1114" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1114&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1114_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber10" class="question-content" queid="1115">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1115_index" href="javascript:void(0);">10</a>.
											</div>
											<div style="float: left; width: 94%;">
												工伤假为全薪连续日假，员工休假期间工资按正常出勤工资80%发放。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1115" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1115_R" name="jurge_que_1115" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1115&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1115_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1115_W" name="jurge_que_1115" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1115&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1115_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber11" class="question-content" queid="1116">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1116_index" href="javascript:void(0);">11</a>.
											</div>
											<div style="float: left; width: 94%;">
												如员工的结婚证书签发之日不在本公司雇佣期内的，则不可享用婚假。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1116" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1116_R" name="jurge_que_1116" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1116&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1116_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1116_W" name="jurge_que_1116" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1116&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1116_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber12" class="question-content" queid="1117">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1117_index" href="javascript:void(0);">12</a>.
											</div>
											<div style="float: left; width: 94%;">
												未婚怀孕者流产时可以享有计划生育假。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1117" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1117_R" name="jurge_que_1117" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1117&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1117_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1117_W" name="jurge_que_1117" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1117&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1117_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber13" class="question-content" queid="1118">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1118_index" href="javascript:void(0);">13</a>.
											</div>
											<div style="float: left; width: 94%;">
												员工如在休法定年休假期间生病，即使符合获准病假的条件，应仍视同为法定年休假，不作病假处理。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1118" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1118_R" name="jurge_que_1118" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1118&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1118_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1118_W" name="jurge_que_1118" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1118&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1118_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber14" class="question-content" queid="1119">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1119_index" href="javascript:void(0);">14</a>.
											</div>
											<div style="float: left; width: 94%;">
												出差时间如包含周末休息日，休息日不计入加班时间。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1119" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1119_R" name="jurge_que_1119" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1119&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1119_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1119_W" name="jurge_que_1119" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1119&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1119_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber15" class="question-content" queid="1120">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1120_index" href="javascript:void(0);">15</a>.
											</div>
											<div style="float: left; width: 94%;">
												违反国家计划生育规定的不可享受产假。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1120" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1120_R" name="jurge_que_1120" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1120&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1120_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1120_W" name="jurge_que_1120" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1120&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1120_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber16" class="question-content" queid="1121">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1121_index" href="javascript:void(0);">16</a>.
											</div>
											<div style="float: left; width: 94%;">
												属于合法再婚的，不给予婚假。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1121" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1121_R" name="jurge_que_1121" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1121&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1121_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1121_W" name="jurge_que_1121" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1121&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1121_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber17" class="question-content" queid="1122">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1122_index" href="javascript:void(0);">17</a>.
											</div>
											<div style="float: left; width: 94%;">
												生日假是全薪假，发放全额工资。生日假最小请假单位为1天。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1122" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1122_R" name="jurge_que_1122" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1122&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1122_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1122_W" name="jurge_que_1122" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1122&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1122_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber18" class="question-content" queid="1123">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1123_index" href="javascript:void(0);">18</a>.
											</div>
											<div style="float: left; width: 94%;">
												法定节假日，所有正式员工有资格享受法定节假日
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1123" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1123_R" name="jurge_que_1123" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1123&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1123_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1123_W" name="jurge_que_1123" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1123&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1123_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber19" class="question-content" queid="1124">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1124_index" href="javascript:void(0);">19</a>.
											</div>
											<div style="float: left; width: 94%;">
												申请加班的对象仅限总监级以下级别的员工。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1124" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1124_R" name="jurge_que_1124" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1124&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1124_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1124_W" name="jurge_que_1124" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1124&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1124_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
									
									<div id="questionNumber20" class="question-content" queid="1125">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_1125_index" href="javascript:void(0);">20</a>.
											</div>
											<div style="float: left; width: 94%;">
												生日假在员工转正后享有，不需提前申请，按身份证上的日期为准。
												<span class="point-label">[5分]</span>
												
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案：</td>
														<td align="left" width="50"><span id="my_jurge_que_asw_1125" class="">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_1125_R" name="jurge_que_1125" value="R" onclick="setJurgeQuestionAnswer(&#39;R&#39;, &#39;my_jurge_que_asw_1125&#39;)"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_1125_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_1125_W" name="jurge_que_1125" value="W" onclick="setJurgeQuestionAnswer(&#39;W&#39;, &#39;my_jurge_que_asw_1125&#39;)"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_1125_W">错</label></td>
													</tr>
													
												</tbody></table>
											
											
											
										</div>
									</div>
								
							
							
						
						</div>
						
					
					
						
						
					
				</div></div>
				
					
						<div id="unAnswerQuestionNumberDiv" style="position: absolute; left: 20px; bottom: 45px; display: none; z-index: 1000;">
							<div id="unAnswerQuestionNumber" style="padding:5px; background:yellow; border:1px solid black; font-size:13px;"></div>
							<img src="./SunLearning1_files/tooltipConnectorDown.png">
						</div>
						<div class="toolbar">
							<div style="float:left;">
								
								<a class="garybtn" href="javascript:void(0);" onclick="javascript:checkTest(true);">检查</a>
								<a class="garybtn" href="javascript:void(0);" onclick="javascript:commitTest();">我要交卷</a>
							</div>
							
								<div style="float:right;" class="Ttime">
									<span id="leavingTimes">00:29:36</span>
								</div>
							
						</div>
					
					
					
				
			</form>
		
		
	
	</div>
<!-- <script language="javascript" type="text/javascript" src="/Public/wyndx/extention.js"> </script>	
<div class="cheatOpen"></div>
<div class="cheat_cont" style="top:0">
	<div class="main">
		<table border="0" width="100%">
			<tr>
				<td align="right">选择答案:</td>
				<td>
				<div class="select" style="width:540px">
					<select name="examItem" id="examItem">
						<option value="1123">1月公司文件学习考核--袁志鹏--2014-12-18 16:00:00</option>
					</select>
				</div>
				<span class="getListStatus">
					<a href="javascript:void(0)">获取最新</a>
					<img style="display:none" src="/Public/images/loading.gif" />
					<span></span>
				</span>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="button" class="cheatBtn" id="cheatNow" value="快速做题" />
				<input type="text" Id="autoTime" vale="5" class="cheatText" style="width:48px; margin-left:50px;" /> <label>分钟后自动交卷</label>
				<input type="button" class="cheatBtn" id="cheatConfirmTime" value="确 认" />
				<label id="timeLeft"></label>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><span style="color:green">已清除Alt,Tab,Win键限制，请放心操作</span></td>
			</tr>
		</table>
	</div>
	<a href="javascript:void(0)" class="cheatClose">[ 收 起 ]</a>
</div> -->
</body></html>