//去掉字符串两端的空格
function trim(Str , Flag){
	Str = ""+Str;
	if( Flag == "l" || Flag == "L" )/*trim left side only*/
	{
		RegularExp = /^\s+/gi;
		return Str.replace( RegularExp,"" );
	}
	else if( Flag == "r" || Flag == "R" )/*trim right side only*/
	{
		RegularExp = /\s+$/gi;
		return Str.replace( RegularExp,"" );
	}
	else/*defautly, trim both left and right side*/
	{
		RegularExp = /^\s+|\s+$/gi;
		return Str.replace( RegularExp,"" );
	}
}

//获取字符串长度
function strlen(str)
{
	str = trim(str);
	var reg = /\r\n/g;
	var str1 = str.replace(reg,""); 
	return str1.length;
}

//获取文本长度，汉字占两个长度
function textlen(text) {
	return text.replace(/[^\x00-\xff]/g, "NB").length;
}

//将日期格式为"2003-08-21"的字符串变为日期对象Date
function covertDate(strDate){
	var tempStr = strDate;
	var strYear, strMonth, strDay, strHour, strMinute;
	if(tempStr.indexOf("-") > -1){  //简体中文日期转换
		// year
		var i = tempStr.indexOf("-");
		if (i > -1) {
			strYear = tempStr.substring(0, i);
			tempStr = tempStr.substring(i + 1, tempStr.length);
		}
		// month
		i = tempStr.indexOf("-");
		if (i > -1) {
			strMonth = tempStr.substring(0, i);
			if(strMonth.indexOf('0') == 0){
				strMonth = strMonth.substring(1, 2);
			}
			strMonth = parseInt(strMonth) - 1;
			tempStr = tempStr.substring(i + 1, tempStr.length);
		}
		// day
		i = tempStr.indexOf(" ");
		if (i > -1) {
			strDay = tempStr.substring(0, i);
			tempStr = tempStr.substring(i + 1, tempStr.length);
		} else {
			strDay = tempStr.substring(i+1, tempStr.length);
			tempStr = '';
		}
	}else if(tempStr.indexOf("/") > -1){
		i = tempStr.indexOf("/");
		if(i < 3){    //英文日期转换
			// month
			if (i > -1) {
				strMonth = tempStr.substring(0, i);
				if(strMonth.indexOf('0') == 0){
					strMonth = strMonth.substring(1, 2);
				}
				strMonth = parseInt(strMonth) - 1;
				tempStr = tempStr.substring(i + 1, tempStr.length);
			}
			// day
			i = tempStr.indexOf("/");
			if (i > -1) {
				strDay = tempStr.substring(0, i);
				if(strDay.indexOf('0') == 0){
					strDay = strDay.substring(1, 2);
				}
				tempStr = tempStr.substring(i + 1, tempStr.length);
			}
			// year
			i = tempStr.indexOf(" ");
			if (i > -1) {
				strYear = tempStr.substring(0, i);
				tempStr = tempStr.substring(i + 1, tempStr.length);
			} else {
				strYear = tempStr.substring(i+1, tempStr.length);
				tempStr = '';
			}	
		}else{     //繁体中文日期转换
			// year
			strYear = tempStr.substring(0, i);
			tempStr = tempStr.substring(i + 1, tempStr.length);
			// month
			i = tempStr.indexOf("/");
			if (i > -1) {
				strMonth = tempStr.substring(0, i);
				if(strMonth.indexOf('0') == 0){
					strMonth = strMonth.substring(1, 2);
				}
				strMonth = parseInt(strMonth) - 1;
				tempStr = tempStr.substring(i + 1, tempStr.length);
			}
			// day
			i = tempStr.indexOf(" ");
			if (i > -1) {
				strDay = tempStr.substring(0, i);
				tempStr = tempStr.substring(i + 1, tempStr.length);
			} else {
				strDay = tempStr.substring(i+1, tempStr.length);
				tempStr = '';
			}
		}
	}
	if(tempStr) {
		tempStr = trim(tempStr);
		// hour
		i = tempStr.indexOf(":");
		if (i > -1) {
			strHour = tempStr.substring(0, i);
			tempStr = tempStr.substring(i + 1, tempStr.length);
		} else {
			strHour = '0';
		}
		// minute
		i = tempStr.indexOf(":");
		if (i > -1) {
			strMinute = tempStr.substring(0, i);
		} else {
			strMinute = tempStr.substring(i+1, tempStr.length);
			tempStr = '';
		}
	}
	strHour = strHour || '0';
	strMinute = strMinute || '0';
	return new Date(strYear, strMonth, strDay, strHour, strMinute);
}

/*
* 改变URL地址参数
* src_url 源地址
* param 要改变的参数
* param_value 要改变的值
*/
function putURLParam(url, name, value) {
	idx0 = url.indexOf('?')
	if (idx0 == -1) {
		paramStr = ''
	} else {
		paramStr = url.substr(idx0,url.length)
	}

	idx1 = paramStr.indexOf(name + '=')
	if (idx1 == -1)	{
		if (paramStr == '') {
			paramStr = '?' + name + '=' + value
		} else {
			paramStr = paramStr + '&' + name + '=' + value
		}
	} else {
		idx1 = idx1 + name.length + 1
		idx2 = paramStr.indexOf('&', idx1)
		if (idx2 == -1) {
			paramStr = paramStr.substr(0,idx1) + value
		} else {
			suffx = paramStr.substr(idx2, paramStr.length)
			paramStr = paramStr.substr(0,idx1) + value + suffx
		}
	}

	if (idx0 == -1) {
		return url + paramStr
	} else {
		return url.substr(0, idx0) + paramStr
	}
}

//获取指定参数的值
String.prototype.getURLParam = function(name)
{
　　var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
　　var r = this.substr(this.indexOf("\?")+1).match(reg);
　　if (r!=null) return unescape(r[2]); return "";
}


/*
 * 导出报表
 * 该函数弹出一个导出报表进度条的层
 * url 导出报表的url地址
 * exportFileName 导出报表的文件名称，不包括后缀名
 */
function popUpProcessbarLayerOfReport(exportBeanName, exportFileName){
	function openWin(f, n, w, h, s){
		var sb = (s == "1") ? "1" : "0";
		var l = (screen.width - w)/2;
		var t = (screen.height - h)/2;
		var sFeatures = "left="+ l +",top="+ t +",height="+ h +",width="+ w + 
		   ",center=1,scrollbars=" + sb + ",status=0,directories=0,channelmode=0";
		var openwin = window.open(f, n, sFeatures);
		if (!openwin.opener){
			openwin.opener = self;
		}
		openwin.focus();
		return openwin;
	}
	
	var url = ctx + '/export.action?url=' + '/commopr/comm!exportReport.action&exportBeanName=' +
			   exportBeanName + '&exportFileName=' + exportFileName;
	for(var n=2; n<arguments.length; n+=2){
		url += ('&' + arguments[n] + '=' + arguments[n + 1]);
	}
	openWin(url, 'exportReport', 400, 220, 0);
}

//不同类型的文件允许的扩展名
var fileExtends = {
	'VIDEO' : 'asx,asf,mpg,wmv,3gp,mp4,mov,avi,flv',
	'AUDIO' : 'mp3,wma',
	'OFFICE' : 'doc,docx,xls,xlsx,ppt,pptx',
	'EXCEL' : 'xls,xlsx',
	'IMAGE' : 'jpg,jpeg,gif,bmp,png',
	'PDF' : 'pdf',
	'FLASH' : 'flash,swf',
	'WEBFILE' : 'zip,rar',
	'SCORM' : 'xml',
	'SCORM2004' : 'xml',
	'ZIP' : 'zip',
	'OTHER' : 'zip,rar,7z',
	'DOCUMENT' : 'doc,docx,xls,xlsx,ppt,pptx,ppsx,pdf,zip,rar,jpg,jpeg,gif,bmp,png,txt',
	'VIDEOS' : 'asx,asf,mpg,wmv,3gp,mp4,mov,avi,flv,swf',
	'SMCX':'smcx'
};

//判断是否是有效的文件
function isValidateFile(obj, fileType){
	if(fileType == 'OTHER'){
		return true;
	}
	var extend = obj.value.substring(obj.value.lastIndexOf(".")+1).toLowerCase();
	if(extend == ''){
		return false;
	}else{
		var fileExtLst = fileExtends[fileType] || fileType;
		var rs = fileExtLst.indexOf(extend);
		if(rs < 0){
			var nf = obj.cloneNode(true);
			nf.value='';
			obj.parentNode.replaceChild(nf, obj);
			return false;
		}
	}
	return true;
}

Object.extend = function(destination, source) {
	for (var property in source) {
		destination[property] = source[property];
	}
	return destination;
}

//弹出对话框
var lmsdialog = function(o) {
	this.options = {
		vArguments : "window",  // 你要向打开的页面传递的数据
		dialogWidth : "650px",
		dialogHeight : "450px",
		center : "yes",
		help : "no",
		resizable : "yes",
		status : "no",
		scroll : "no"
	};
	Object.extend(this.options, o || {});
}
lmsdialog.prototype = {
	open : function(url) {
		var options = this.options;
		var sp = "dialogWidth:" + options["dialogWidth"] + ";dialogHeight:"
				+ options["dialogHeight"] + ";center:" + options["center"]
				+ ";help:" + options["help"] + ";resizable:"
				+ options["resizable"] + ";status:" + options["status"]
				+ ";scroll:" + options["scroll"];
		var newWin = window.showModalDialog(options["url"],
				options["vArguments"], sp);
	}
}

//弹出窗口
var lmswindow = function(o) {
	this.options = {
		width : 650,
		height : 450,
		toolbar : "no",
		scrollbars : "auto",
		resizable : "yes",
		status : "no",
		menubar : "no",
		location : "no",
		fullscreen : false,
		center : true
	};
	Object.extend(this.options, o || {});
}
lmswindow.prototype = {
	open : function(url, name) {
		var options = this.options;
		var settings = "width=" + options["width"] + ",height=" + options["height"]
				+ ",innerWidth=" + options["width"] + ",innerHeight=" + options["height"] 
				+ ",toolbar=" + options["toolbar"] + ",scrollbars=" + options["scrollbars"]
				+ ",resizable=" + options["resizable"] + ",status=" + options["status"]
				+ ",menubar=" + options["menubar"];
		if(options["center"] == true){
			var iTop = (window.screen.availHeight-30-options["height"])/2;
			var iLeft = (window.screen.availWidth-10-options["width"])/2;
			settings += + ",top=" + iTop + ",left=" + iLeft;
		}
		var newWin = window.open(url, name, settings);
		if(options["fullscreen"] == true){
			newWin.moveTo(0, 0);
			newWin.resizeTo(screen.availWidth, screen.availHeight);
		}
		newWin.focus();
		return newWin;
	}
}

//字符串替换
String.prototype.replaceAll = function(oldstr, newstr){
	var result = this.replace(oldstr, newstr);
	if(result.indexOf(oldstr) != -1) {
		return result.replaceAll(oldstr, newstr);
	}
	return result;
}

//显示正在装载层
function showLoadingMask(containerId, loadingMsg, relativePosition){
	var wrap = containerId ? $("#"+containerId) : $("body");
	var left = relativePosition ? 0 : wrap.offset().left;
	var top = relativePosition ? 0 : wrap.offset().top;
	var scrollTop = containerId ? 0 : $(document).scrollTop();
	$("<div class=\"lms-mask\"></div>").css({
		display: "block",
		width: relativePosition ? wrap.outerWidth() : wrap.width(),
		height: relativePosition ? wrap.outerHeight() : wrap.height(),
		left: left,
		top: top
	}).appendTo(wrap);
	if(loadingMsg){
		$("<div class=\"lms-mask-msg\"></div>").html(loadingMsg).appendTo(wrap).css({
			display: "block",
			left: (wrap.width() - ($("div.lms-mask-msg", wrap).outerWidth() == 0 ? 170 : $("div.lms-mask-msg", wrap).outerWidth()))/2 + left,
			top: ((wrap.height() > $(window).height() && !containerId ? $(window).height() : wrap.height())  - $("div.lms-mask-msg", wrap).outerHeight())/2 + top + scrollTop
		});
	}else{
		$("div.lms-mask", wrap).css('z-index', '110005');
		$("<div class=\"lms-mask-nomsg\"></div>").appendTo(wrap).css({
			display: "block",
			left: (wrap.width() - ($("div.lms-mask-nomsg", wrap).outerWidth() == 0 ? 170 : $("div.lms-mask-nomsg", wrap).outerWidth()))/2 + left,
			top: ((wrap.height() > $(window).height() && !containerId ? $(window).height() : wrap.height()) - $("div.lms-mask-nomsg", wrap).outerHeight())/2 + top + scrollTop
		});
	}
}

//直接隐藏正在装载层
function hideLoadingMask(){
	if($("div.lms-mask-msg").length > 0){
		$("div.lms-mask-msg").remove();
	}else if($("div.lms-mask-nomsg").length > 0){
		$("div.lms-mask-nomsg").remove();
	}
	$("div.lms-mask").remove();
}

//渐变隐藏正在装载层
function fadeOutLoadingMask(){
	if($("div.lms-mask-msg").length > 0){
		$("div.lms-mask-msg").fadeOut("slow", function(){
			$("div.lms-mask-msg").remove();
		});
	}else if($("div.lms-mask-nomsg").length > 0){
		$("div.lms-mask-nomsg").fadeOut("slow", function(){
			$("div.lms-mask-nomsg").remove();
		});
	}
	$("div.lms-mask").fadeOut("slow", function(){
		$("div.lms-mask").remove();
	});
}

//判断HTML编辑面板中的内容是否为空
function isXheditorEmpty(content){
	var contenStringLower = content.toLowerCase();
	return contenStringLower == '<p>&nbsp;</p>' || contenStringLower == '&nbsp;' 
		|| contenStringLower == '' || contenStringLower == '<br>' || contenStringLower == '<p><br></p>';
}

//隐藏横向滚动条
function hideScrollBarX(scrollElementSelector, innerElementSelector) {
	if ($.browser.msie && $.browser.version < 7.0) {
		var hasScrollBar = $(scrollElementSelector).data('hasScrollBar');
		if (!hasScrollBar && $(innerElementSelector).height() > $(scrollElementSelector).height()) {
			$(innerElementSelector).width($(innerElementSelector).width() - 17);
			$(scrollElementSelector).css('overflowX', 'hidden');
			$(scrollElementSelector).data('hasScrollBar', '1');
		} else if (hasScrollBar && $(innerElementSelector).height() < $(scrollElementSelector).height()) {
			$(innerElementSelector).width($(innerElementSelector).width() + 17);
			$(scrollElementSelector).data('hasScrollBar', null);
		}
	}
}

//解决IE6下显示透明背景的png图片问题
function enablePngImages() {
	if ($.browser.msie && $.browser.version < 7.0) {
		var imgArr = document.getElementsByTagName("IMG");
		for(var i=0; i<imgArr.length; i++){
			if(imgArr[i] && imgArr[i].src.toLowerCase().lastIndexOf(".png") != -1){
				imgArr[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + imgArr[i].src + "', sizingMethod='auto')";
				imgArr[i].src = "spacer.gif";
			}
			if(imgArr[i] && imgArr[i].currentStyle.backgroundImage.lastIndexOf(".png") != -1){
				var img = imgArr[i].currentStyle.backgroundImage.substring(5,imgArr[i].currentStyle.backgroundImage.length-2);
				imgArr[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+img+"', sizingMethod='crop')";
				imgArr[i].style.backgroundImage = "url("+ctx+"/image/blank.gif)";
			} 
		} 
	}
}

//解决IE6下将透明背景的png图片作为背景的显示问题
function enableBgPngImages(bgElements){
	if ($.browser.msie && $.browser.version < 7.0 && bgElements) {
		if(typeof bgElements == 'object' && bgElements.constructor != Array){
			bgElements = new Array(bgElements);
		}
		for(var i=0; i<bgElements.length; i++){
			if(bgElements[i] && bgElements[i].currentStyle.backgroundImage.lastIndexOf(".png") != -1){
				var img = bgElements[i].currentStyle.backgroundImage.substring(5,bgElements[i].currentStyle.backgroundImage.length-2);
				bgElements[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+img+"', sizingMethod='crop')";
				bgElements[i].style.backgroundImage = "url("+ctx+"/image/blank.gif)";
			}
		}
	}
}
//验证typecode是什么类型（此处应用在首页分享知识）
function typeCodeSource(obj, codeValue){
	
	var extend = obj.value.substring(obj.value.lastIndexOf(".")+1).toLowerCase();
	var typeCode = codeValue.value;
	if(validFileConsFileType(extend,'VIDEO')){
		typeCode = 'VIDEO';
	}else if(validFileConsFileType(extend,'AUDIO')){
		typeCode = 'AUDIO';
	}else if(validFileConsFileType(extend,'FLASH')){
		typeCode = 'FLASH';
	}else if(validFileConsFileType(extend,'OFFICE')){
		typeCode = 'OFFICE';
	}else if(validFileConsFileType(extend,'IMAGE')){
		typeCode = 'IMAGE';
	}else if(validFileConsFileType(extend,'PDF')){
		typeCode = 'PDF';
	}else if(validFileConsFileType(extend,'WEBFILE')){
		typeCode = 'WEBFILE';
	}else{
		typeCode = 'OTHER';
	}
	return typeCode;
}
function validFileConsFileType(extend, fileType){
	var fileExtLst = fileExtends[fileType];
	var rs = fileExtLst.indexOf(extend);
	if(rs >= 0){
		return true;
	}
	return false;
}
function limitTextLength(obj){
	if($.trim(obj.value).length > 200){
		obj.value = obj.value.substring(0,199);
	} 
}