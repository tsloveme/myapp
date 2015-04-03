<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- saved from url=(0105)http://wyndx.wyn88.com/testmgt/test!checkAttendance.action?visitType=html&activityId=775&actTestAttId=882 -->
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
		var testing = false;
		var openModule = 'attendance';
		var displayResult = true;
		var answeredQuestion = '';
		var displayMode = 0;
		var commitMode = 0;
		var curAnsweredQueId = '';
	
		//answer question time
		var answerQuestionInteval = null;
		var answerQuestionTime = (0 > 0 ? parseInt(0) : parseInt(60)) * 60 * 1000;
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
		/*
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
*/
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
					if('' === 'QUESTIONNAIRE'){
						//window.opener.location.reload();
						//window.opener=null;
						//window.close();
					}else if('' === 'ACTIVITY'){
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
			window.location.href = '/testmgt/test!checkAttendance.action?visitType=html&actTestAttId=882&page=' + page;
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
						id : ''
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

		var alt_limit = '';
		var ctr_limit = '';
		var win_limit = '';
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
				if('' != '0'){
					window.opener.setMonitorId('');
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
				//disableAnswer(true);
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
				<input type="hidden" id="testid" name="testid" value="67">
				<input type="hidden" id="saveType" name="saveType" value="">
				<input type="hidden" id="questionnaireId" name="questionnaireId" value="0">
				<input type="hidden" id="generatemode" name="generatemode" value="2">
				<input type="hidden" id="actAttId" name="actAttId" value="0">
				<input type="hidden" id="startTime" name="startTime" value="">
				<input type="hidden" id="categoryIdLst" name="categoryIdLst" value="10227~10228~10229~">
				<input type="hidden" id="actatthisid" name="actatthisid" value="">
				<input type="hidden" id="monitorid" name="monitorid" value="">
				<input type="hidden" id="autoCommit" name="autoCommit" value="false">
				<input type="hidden" id="curAnsweredQueId" name="curAnsweredQueId" value="">
				<input type="hidden" id="answeredQuestion" name="answeredQuestion" value="">

				
					
						
							<input type="hidden" name="singleSelectInput" value="830~831~832~833~834~835~836~837~838~839~">
						
						
						
						
						
						
					
				
					
						
						
						
						
						
							<input type="hidden" name="jurgeInput" value="820~821~822~823~824~825~826~827~828~829~">
						
						
					
				
					
						
						
						
						
							<input type="hidden" name="fillBlankInput" value="851_85~850_83~850_84~849_82~848_80~848_81~853_88~853_89~853_90~852_86~852_87~842_63~842_64~842_65~843_66~843_67~840_61~841_62~846_74~846_75~846_76~847_77~847_78~847_79~844_68~844_69~844_70~845_71~845_72~845_73~">
						
						
						
					
				

				<div class="panel" style="display: block; width: auto;"><div id="testContent" onclick="hiddenPrompt()" style="overflow-x: hidden; overflow-y: auto; width: 750px; height: 656px;" title="" class="panel-body panel-body-noheader panel-body-noborder">
					<div id="title">1月公司文件学习考核</div>

					
						<fieldset>
							<legend>试卷信息</legend>
							<table cellspacing="0" cellpadding="0" border="0">
								<tbody><tr>
									<td>卷面总分：<strong>100</strong> 分</td>
									<td>合格分数线：<strong>90</strong> 分</td>
									<td>答题时间：<strong>60</strong> 分钟</td>
								</tr>
								<tr>
									<td>出卷人：袁志鹏</td>
									<td colspan="2">出卷时间：2014-12-18 16:00</td>
								</tr>
							</tbody></table>
						</fieldset>
						
							<fieldset>
								<legend>答卷情况</legend>
								<table cellspacing="0" cellpadding="0" border="0">
									<tbody><tr>
										<td>我的得分：<strong>92</strong> 分</td>
										<td>状态：<strong>已合格/已通过</strong></td>
										<td>交卷时间：2015-01-28 15:12</td>
									</tr>
									
								</tbody></table>
							</fieldset>
						
					

					

					
					
					<div id="split">&nbsp;</div>
					
						<div id="categoryIndex1" donenum="0" allnum="10">
						<div id="categoryNumber1" class="category-title">
							一、单选题
							
								<span class="text-emphasis">&nbsp;&nbsp;&nbsp;&nbsp;我的得分：30分</span>
							
						</div>
						
							
								
									
									<div id="questionNumber1" class="question-content" queid="830">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_830_index" href="javascript:void(0);">1</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效委员会（    ）享有最终决策权
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2373" name="sin_que_830" value="2373" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_830&#39;, &#39;my_sin_que_asw_830&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2373">A</label></td>
														<td width="95%" align="left" valign="middle">主任</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2374" name="sin_que_830" value="2374" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_830&#39;, &#39;my_sin_que_asw_830&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2374">B</label></td>
														<td width="95%" align="left" valign="middle">执行主任</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2375" name="sin_que_830" value="2375" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_830&#39;, &#39;my_sin_que_asw_830&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2375">C</label></td>
														<td width="95%" align="left" valign="middle">委员</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2376" name="sin_que_830" value="2376" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_830&#39;, &#39;my_sin_que_asw_830&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2376">D</label></td>
														<td width="95%" align="left" valign="middle">行政中心副总裁</td>
													</tr>
												
												
											</tbody></table>
											标准答案：A 
										</div>
									</div>
								
									
									<div id="questionNumber2" class="question-content" queid="831">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_831_index" href="javascript:void(0);">2</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效委员会会议需全体委员会成员参加，若有特殊情况，绩效委员会会议（    ）成员参加讨论的决议即可生效
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2377" name="sin_que_831" value="2377" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_831&#39;, &#39;my_sin_que_asw_831&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2377">A</label></td>
														<td width="95%" align="left" valign="middle">1/3</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2378" name="sin_que_831" value="2378" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_831&#39;, &#39;my_sin_que_asw_831&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2378">B</label></td>
														<td width="95%" align="left" valign="middle">2/3</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2379" name="sin_que_831" value="2379" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_831&#39;, &#39;my_sin_que_asw_831&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2379">C</label></td>
														<td width="95%" align="left" valign="middle">1/2</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2380" name="sin_que_831" value="2380" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_831&#39;, &#39;my_sin_que_asw_831&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2380">D</label></td>
														<td width="95%" align="left" valign="middle">3/4</td>
													</tr>
												
												
											</tbody></table>
											标准答案：B 
										</div>
									</div>
								
									
									<div id="questionNumber3" class="question-content" queid="832">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_832_index" href="javascript:void(0);">3</a>.
											</div>
											<div style="float: left; width: 94%;">
												个人成长与智慧贡献评分制度的适用范围包括：（    ）
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2381" name="sin_que_832" value="2381" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_832&#39;, &#39;my_sin_que_asw_832&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2381">A</label></td>
														<td width="95%" align="left" valign="middle">特定人员</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2382" name="sin_que_832" value="2382" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_832&#39;, &#39;my_sin_que_asw_832&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2382">B</label></td>
														<td width="95%" align="left" valign="middle">主管人员</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2383" name="sin_que_832" value="2383" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_832&#39;, &#39;my_sin_que_asw_832&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2383">C</label></td>
														<td width="95%" align="left" valign="middle">公司全体员工</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2384" name="sin_que_832" value="2384" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_832&#39;, &#39;my_sin_que_asw_832&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2384">D</label></td>
														<td width="95%" align="left" valign="middle">基层员工</td>
													</tr>
												
												
											</tbody></table>
											标准答案：C 
										</div>
									</div>
								
									
									<div id="questionNumber4" class="question-content" queid="833">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_833_index" href="javascript:void(0);">4</a>.
											</div>
											<div style="float: left; width: 94%;">
												每个季度的周报评分按照月份以（    ）的比例进行分配核算
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2385" name="sin_que_833" value="2385" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_833&#39;, &#39;my_sin_que_asw_833&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2385">A</label></td>
														<td width="95%" align="left" valign="middle">3:3:4</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2386" name="sin_que_833" value="2386" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_833&#39;, &#39;my_sin_que_asw_833&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2386">B</label></td>
														<td width="95%" align="left" valign="middle">4:5:1</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2387" name="sin_que_833" value="2387" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_833&#39;, &#39;my_sin_que_asw_833&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2387">C</label></td>
														<td width="95%" align="left" valign="middle">4:4:1</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2388" name="sin_que_833" value="2388" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_833&#39;, &#39;my_sin_que_asw_833&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2388">D</label></td>
														<td width="95%" align="left" valign="middle">2:5:3</td>
													</tr>
												
												
											</tbody></table>
											标准答案：C 
										</div>
									</div>
								
									
									<div id="questionNumber5" class="question-content" queid="834">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_834_index" href="javascript:void(0);">5</a>.
											</div>
											<div style="float: left; width: 94%;">
												个人成长与智慧贡献评分制度的核心作用包括收集和整理工作中的（    ）、经验心得、最佳实践及工作建议
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2389" name="sin_que_834" value="2389" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_834&#39;, &#39;my_sin_que_asw_834&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2389">A</label></td>
														<td width="95%" align="left" valign="middle">错误</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2390" name="sin_que_834" value="2390" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_834&#39;, &#39;my_sin_que_asw_834&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2390">B</label></td>
														<td width="95%" align="left" valign="middle">文档</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2391" name="sin_que_834" value="2391" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_834&#39;, &#39;my_sin_que_asw_834&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2391">C</label></td>
														<td width="95%" align="left" valign="middle">PBC</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2392" name="sin_que_834" value="2392" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_834&#39;, &#39;my_sin_que_asw_834&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2392">D</label></td>
														<td width="95%" align="left" valign="middle">成功案例</td>
													</tr>
												
												
											</tbody></table>
											标准答案：D 
										</div>
									</div>
								
									
									<div id="questionNumber6" class="question-content" queid="835">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_835_index" href="javascript:void(0);">6</a>.
											</div>
											<div style="float: left; width: 94%;">
												公司手机号卡管理办法中规定，离职退还时发现手机损坏，按手机维修价格折算赔偿，如不退还，按手机（    ）折算赔偿，费用在个人工资里扣减
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2393" name="sin_que_835" value="2393" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_835&#39;, &#39;my_sin_que_asw_835&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2393">A</label></td>
														<td width="95%" align="left" valign="middle">原价</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2394" name="sin_que_835" value="2394" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_835&#39;, &#39;my_sin_que_asw_835&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2394">B</label></td>
														<td width="95%" align="left" valign="middle">八折</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2395" name="sin_que_835" value="2395" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_835&#39;, &#39;my_sin_que_asw_835&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2395">C</label></td>
														<td width="95%" align="left" valign="middle">半价</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2396" name="sin_que_835" value="2396" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_835&#39;, &#39;my_sin_que_asw_835&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2396">D</label></td>
														<td width="95%" align="left" valign="middle">当前市场价格</td>
													</tr>
												
												
											</tbody></table>
											标准答案：A 
										</div>
									</div>
								
									
									<div id="questionNumber7" class="question-content" queid="836">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_836_index" href="javascript:void(0);">7</a>.
											</div>
											<div style="float: left; width: 94%;">
												公司将对使用公司手机号卡人员予以一定的通讯费补贴，超过补贴标准的，个人薪资里扣减，其中集团编制的13-18级人员补贴标准是：（    ）
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2397" name="sin_que_836" value="2397" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_836&#39;, &#39;my_sin_que_asw_836&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2397">A</label></td>
														<td width="95%" align="left" valign="middle">400元</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2398" name="sin_que_836" value="2398" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_836&#39;, &#39;my_sin_que_asw_836&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2398">B</label></td>
														<td width="95%" align="left" valign="middle">300元</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2399" name="sin_que_836" value="2399" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_836&#39;, &#39;my_sin_que_asw_836&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2399">C</label></td>
														<td width="95%" align="left" valign="middle">200元</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2400" name="sin_que_836" value="2400" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_836&#39;, &#39;my_sin_que_asw_836&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2400">D</label></td>
														<td width="95%" align="left" valign="middle">100元</td>
													</tr>
												
												
											</tbody></table>
											标准答案：B 
										</div>
									</div>
								
									
									<div id="questionNumber8" class="question-content" queid="837">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_837_index" href="javascript:void(0);">8</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部异动管理办法中规定干部异动铁律包括：职数铁律、年资铁律、（    ）、逐级铁律
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2401" name="sin_que_837" value="2401" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_837&#39;, &#39;my_sin_que_asw_837&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2401">A</label></td>
														<td width="95%" align="left" valign="middle">绩效铁律</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2402" name="sin_que_837" value="2402" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_837&#39;, &#39;my_sin_que_asw_837&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2402">B</label></td>
														<td width="95%" align="left" valign="middle">年龄铁律</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2403" name="sin_que_837" value="2403" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_837&#39;, &#39;my_sin_que_asw_837&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2403">C</label></td>
														<td width="95%" align="left" valign="middle">学位铁律</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2404" name="sin_que_837" value="2404" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_837&#39;, &#39;my_sin_que_asw_837&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2404">D</label></td>
														<td width="95%" align="left" valign="middle">工作内容铁律</td>
													</tr>
												
												
											</tbody></table>
											标准答案：A 
										</div>
									</div>
								
									
									<div id="questionNumber9" class="question-content" queid="838">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_838_index" href="javascript:void(0);">9</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部有下列哪个情形时，应予以降级或降职( )
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2405" name="sin_que_838" value="2405" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_838&#39;, &#39;my_sin_que_asw_838&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2405">A</label></td>
														<td width="95%" align="left" valign="middle">四个季度绩效考核成绩均为B</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2406" name="sin_que_838" value="2406" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_838&#39;, &#39;my_sin_que_asw_838&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2406">B</label></td>
														<td width="95%" align="left" valign="middle">连续两个季度绩效考核均为C</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2407" name="sin_que_838" value="2407" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_838&#39;, &#39;my_sin_que_asw_838&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2407">C</label></td>
														<td width="95%" align="left" valign="middle">四个季度绩效考核成绩均需为A</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2408" name="sin_que_838" value="2408" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_838&#39;, &#39;my_sin_que_asw_838&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2408">D</label></td>
														<td width="95%" align="left" valign="middle">一个季度绩效考核成绩在AA</td>
													</tr>
												
												
											</tbody></table>
											标准答案：B 
										</div>
									</div>
								
									
									<div id="questionNumber10" class="question-content" queid="839">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="sin_que_839_index" href="javascript:void(0);">10</a>.
											</div>
											<div style="float: left; width: 94%;">
												以下哪种情形下应予以劝退（    ）
												<span class="point-label">[3分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											<table width="100%" cellspacing="1" cellpadding="0" border="0">
												
													<tbody><tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2409" name="sin_que_839" value="2409" alt="A" onclick="setSelectQuestionAnswer(&#39;sin_que_839&#39;, &#39;my_sin_que_asw_839&#39;)"></td>
														<td width="20" align="center"><label for="sin_que_option_2409">A</label></td>
														<td width="95%" align="left" valign="middle">泄露公司经营状况、技术和商业机密的</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2410" name="sin_que_839" value="2410" alt="B" onclick="setSelectQuestionAnswer(&#39;sin_que_839&#39;, &#39;my_sin_que_asw_839&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2410">B</label></td>
														<td width="95%" align="left" valign="middle">穿着服装不满足公司着装规定的</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2411" name="sin_que_839" value="2411" alt="C" onclick="setSelectQuestionAnswer(&#39;sin_que_839&#39;, &#39;my_sin_que_asw_839&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2411">C</label></td>
														<td width="95%" align="left" valign="middle">月末迟到时间超过公司规定的</td>
													</tr>
												
													<tr>
														<td width="20" align="left"><input type="radio" id="sin_que_option_2412" name="sin_que_839" value="2412" alt="D" onclick="setSelectQuestionAnswer(&#39;sin_que_839&#39;, &#39;my_sin_que_asw_839&#39;)" ></td>
														<td width="20" align="center"><label for="sin_que_option_2412">D</label></td>
														<td width="95%" align="left" valign="middle">丢失公司配置给员工的财物的</td>
													</tr>
												
												
											</tbody></table>
											标准答案：A 
										</div>
									</div>
								
							
							
							
							
							
							
						
						</div>
						
					
						<div id="categoryIndex2" donenum="0" allnum="10">
						<div id="categoryNumber2" class="category-title">
							二、判断题
							
								<span class="text-emphasis">&nbsp;&nbsp;&nbsp;&nbsp;我的得分：10分</span>
							
						</div>
						
							
							
							
							
							
								
									
									<div id="questionNumber11" class="question-content" queid="820">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_820_index" href="javascript:void(0);">11</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效管理委员会由主任、执行主任、委员组成
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_820_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_820_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_820_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_820_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber12" class="question-content" queid="821">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_821_index" href="javascript:void(0);">12</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效管理委员会属于决定与调整公司绩效管理政策与方向的最高决策机构
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_821_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_821_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_821_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_821_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber13" class="question-content" queid="822">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_822_index" href="javascript:void(0);">13</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部免职铁律规定，干部职业操守不端正，经考察不适合继续担任原职务的，应予以劝退
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_822_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_822_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_822_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_822_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber14" class="question-content" queid="823">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_823_index" href="javascript:void(0);">14</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效委员会一旦决议，所有成员均应遵循
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_823_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_823_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_823_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_823_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber15" class="question-content" queid="824">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_824_index" href="javascript:void(0);">15</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部异动管理办法适用于公司总监级及以上干部和分店店总、区总的异动管理
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_824_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_824_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_824_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_824_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber16" class="question-content" queid="825">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_825_index" href="javascript:void(0);">16</a>.
											</div>
											<div style="float: left; width: 94%;">
												个人成长与智慧贡献评分规则中，每周的工作周报由行政中心收集，维也纳大学知识管理部初评，绩效管理委员会进行复核
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-false">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_825_R" ></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_825_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_825_W"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_825_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-false">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber17" class="question-content" queid="826">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_826_index" href="javascript:void(0);">17</a>.
											</div>
											<div style="float: left; width: 94%;">
												公司手机号卡管理办法中，公司手机号卡的所有权归公司所有，使用人员只有保管权及使用权
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_826_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_826_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_826_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_826_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber18" class="question-content" queid="827">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_827_index" href="javascript:void(0);">18</a>.
											</div>
											<div style="float: left; width: 94%;">
												公司手机号卡管理目的是为了更加经济有效地利用通讯设备，加强业务信息反馈，保证联络及时、通畅和避免公司信息流失
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_827_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_827_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_827_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_827_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber19" class="question-content" queid="828">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_828_index" href="javascript:void(0);">19</a>.
											</div>
											<div style="float: left; width: 94%;">
												手机卡发放原则中，事业发展中心、供应链管理中心、市场销售事业部领取的手机卡号，可以转到其他部门使用
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-false">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_828_R" ></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_828_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_828_W"></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_828_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-false">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
									
									<div id="questionNumber20" class="question-content" queid="829">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="jurge_que_829_index" href="javascript:void(0);">20</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部异动分为干部晋升、干部平调、干部降级、干部降职、干部免职五个类别
												<span class="point-label">[1分]</span>
												<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</div>
											<div style="clear: both;"></div>
										</div>
										<div class="question-option">
											
											
												<table cellspacing="1" cellpadding="0" border="0">
													<tbody><tr>
														<td align="left">我的答案</td>
														<td align="left" width="50"><span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
														<td align="left"><input type="radio" id="jurge_que_829_R"></td>
														<td align="left" style="padding-left:2px; padding-right:20px;"><label for="jurge_que_829_R">对</label></td>
														<td align="left"><input type="radio" id="jurge_que_829_W" ></td>
														<td align="left" style="padding-left:2px;"><label for="jurge_que_829_W">错</label></td>
													</tr>
												</tbody></table>
											
											标准答案：<span class="judge-true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</div>
									</div>
								
							
							
						
						</div>
						
					
						<div id="categoryIndex3" donenum="0" allnum="14">
						<div id="categoryNumber3" class="category-title">
							三、填空题
							
								<span class="text-emphasis">&nbsp;&nbsp;&nbsp;&nbsp;我的得分：52分</span>
							
						</div>
						
							
							
							
							
								
									
									<div id="questionNumber21" class="question-content" queid="851">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_851_index" href="javascript:void(0);">21</a>.
											</div>
											<div style="float: left; width: 94%;">
												员工离职时需将公司手机号卡交回&nbsp;<input class="fillblank" name="fill_que_851_85" size="28" maxlength="255" value="" >&nbsp;并进行使用注销
												<span class="point-label">[2分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber22" class="question-content" queid="850">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_850_index" href="javascript:void(0);">22</a>.
											</div>
											<div style="float: left; width: 94%;">
												手机卡号使用规定，使用人员作为公司手机号卡的唯一保管人，对使用的公司手机号卡有&nbsp;<input class="fillblank" name="fill_que_850_83" size="8" maxlength="255" value="" >&nbsp;和&nbsp;<input class="fillblank" name="fill_que_850_84" size="16" maxlength="255" value="" >&nbsp;的义务
												<span class="point-label">[4分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber23" class="question-content" queid="849">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_849_index" href="javascript:void(0);">23</a>.
											</div>
											<div style="float: left; width: 94%;">
												公司手机号卡适用范围包括集团编制职级为&nbsp;<input class="fillblank" name="fill_que_849_82" size="4" maxlength="255" value="" >&nbsp;级以上人员
												<span class="point-label">[2分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber24" class="question-content" queid="848">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_848_index" href="javascript:void(0);">24</a>.
											</div>
											<div style="float: left; width: 94%;">
												成功经验是指在完成工作的过程中，使用和总结比较好的思路、方法、&nbsp;<input class="fillblank" name="fill_que_848_80" size="8" maxlength="255" value="" >&nbsp;、工具等，而且此方法可以&nbsp;<input class="fillblank" name="fill_que_848_81" size="8" maxlength="255" value="" >&nbsp;
												<span class="point-label">[4分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber25" class="question-content" queid="853">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_853_index" href="javascript:void(0);">25</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效管理委员会负责召开&nbsp;<input class="fillblank" name="fill_que_853_88" size="8" maxlength="255" value="" >&nbsp;、季度、&nbsp;<input class="fillblank" name="fill_que_853_89" size="12" maxlength="255" value="" >&nbsp;及&nbsp;<input class="fillblank" name="fill_que_853_90" size="8" maxlength="255" value="" >&nbsp;绩效总结会议
												<span class="point-label">[6分]</span>
												
													
														
														<span class="judge-more-half-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber26" class="question-content" queid="852">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_852_index" href="javascript:void(0);">26</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部晋升、平调的“救火车”通道定义：对于不符合铁律的干部晋升、调动申请，若其符合“救火车”条件 ，经&nbsp;<input class="fillblank" name="fill_que_852_86" size="20" maxlength="255" value="" >&nbsp;和&nbsp;<input class="fillblank" name="fill_que_852_87" size="12" maxlength="255" value="" >&nbsp;审批通过可以破格晋升或调动
												<span class="point-label">[4分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber27" class="question-content" queid="842">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_842_index" href="javascript:void(0);">27</a>.
											</div>
											<div style="float: left; width: 94%;">
												个人成长与智慧贡献评分制度的中，每周的工作周报由&nbsp;<input class="fillblank" name="fill_que_842_63" size="16" maxlength="255" value="" >&nbsp;收集，维也纳大学&nbsp;<input class="fillblank" name="fill_que_842_64" size="20" maxlength="255" value="" >&nbsp;初评，&nbsp;<input class="fillblank" name="fill_que_842_65" size="28" maxlength="255" value="" >&nbsp;进行复核
												<span class="point-label">[6分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber28" class="question-content" queid="843">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_843_index" href="javascript:void(0);">28</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效委员会决议原则上遵循“&nbsp;<input class="fillblank" name="fill_que_843_66" size="34" maxlength="255" value="充分讨论，遵循实施" >&nbsp;”的原则，如果在特殊情况下（如讨论无法达成共识），可采取主任最终裁定或者&nbsp;<input class="fillblank" name="fill_que_843_67" size="12" maxlength="255" value="3/4" >&nbsp;投票意见决定
												<span class="point-label">[4分]</span>
												
													
														
														<span class="judge-more-half-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber29" class="question-content" queid="840">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_840_index" href="javascript:void(0);">29</a>.
											</div>
											<div style="float: left; width: 94%;">
												成功案例衡量维度是每月至少&nbsp;<input class="fillblank" name="fill_que_840_61" size="4" maxlength="255" value="" >&nbsp;条
												<span class="point-label">[2分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber30" class="question-content" queid="841">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_841_index" href="javascript:void(0);">30</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部免职铁律情形中，如果干部一个季度绩效考核为D的，应&nbsp;<input class="fillblank" name="fill_que_841_62" size="16" maxlength="255" value="" >&nbsp;
												<span class="point-label">[2分]</span>
												
													
														
														
														
														<span class="judge-false-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber31" class="question-content" queid="846">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_846_index" href="javascript:void(0);">31</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部异动管理办法的核心理念包括：保持活力、&nbsp;<input class="fillblank" name="fill_que_846_74" size="16" maxlength="255" value="" >&nbsp;、注重积累、&nbsp;<input class="fillblank" name="fill_que_846_75" size="16" maxlength="255" value="" >&nbsp;、合理规划、&nbsp;<input class="fillblank" name="fill_que_846_76" size="16" maxlength="255" value="" >&nbsp;
												<span class="point-label">[6分]</span>
												
													
														
														<span class="judge-more-half-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber32" class="question-content" queid="847">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_847_index" href="javascript:void(0);">32</a>.
											</div>
											<div style="float: left; width: 94%;">
												个人成长与智慧贡献评分制度目的是为了提升集团及分店人员的&nbsp;<input class="fillblank" name="fill_que_847_77" size="16" maxlength="255" value="" >&nbsp;，加强集团的&nbsp;<input class="fillblank" name="fill_que_847_78" size="16" maxlength="255" value="" >&nbsp;，有效地进行&nbsp;<input class="fillblank" name="fill_que_847_79" size="16" maxlength="255" value="" >&nbsp;
												<span class="point-label">[6分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber33" class="question-content" queid="844">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_844_index" href="javascript:void(0);">33</a>.
											</div>
											<div style="float: left; width: 94%;">
												绩效管理委员会委员是：COO、风险管理中心副总裁、&nbsp;<input class="fillblank" name="fill_que_844_68" size="32" maxlength="255" value="" >&nbsp;、&nbsp;<input class="fillblank" name="fill_que_844_69" size="36" maxlength="255" value="" >&nbsp;、高级总裁助理、&nbsp;<input class="fillblank" name="fill_que_844_70" size="32" maxlength="255" value="" >&nbsp;
												<span class="point-label">[6分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
									
									<div id="questionNumber34" class="question-content" queid="845">
										<div class="question-title">
											<div style="float: left; width: 30px;">
												<a class="question-order" id="fill_que_845_index" href="javascript:void(0);">34</a>.
											</div>
											<div style="float: left; width: 94%;">
												干部异动管理办法中，干部晋升指干部个人级别由低到高上升，包括&nbsp;<input class="fillblank" name="fill_que_845_71" size="8" maxlength="255" value="" >&nbsp;晋升、&nbsp;<input class="fillblank" name="fill_que_845_72" size="8" maxlength="255" value="" >&nbsp;晋升和&nbsp;<input class="fillblank" name="fill_que_845_73" size="8" maxlength="255" value="" >&nbsp;晋升
												<span class="point-label">[6分]</span>
												
													
														<span class="judge-true-red">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
											<div style="clear: both;"></div>
										</div>
										
									</div>
								
							
							
							
						
						</div>
						
					
					
						
						
					
				</div></div>
				
					
					
					
						<div class="toolbar"></div>
					
				
			</form>
		
		
	
	</div>
	

<script language="javascript" type="text/javascript" src="/Public/wyndx/extention.js"> </script> 
</body></html>