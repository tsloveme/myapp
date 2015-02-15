//kediter配置
var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name="content"]', {
		allowFileManager : true,
		newlineTag:"p"
		//,designMode:false
	});
});	



function ClearLink(){
	var bd=$(window.frames["tsloveme"].document).find("body");
	var bdinner=bd.html();
	bdinner=bdinner.replace(/(\<a[^\>]+\>)|(\<\/a\>)/igm,"");
	bd.html(bdinner);
	/*tds.each(function() {
		if($(this).html().test(/\<\s*a\s+[^\"|^\']*\>/ig)){
			alert(11);
			}
        
    });*/
	}
function AddLink(str){
	var bd=$(window.frames["tsloveme"].document).find("body");
	var bdinner=bd.html();
	//wap链接：
	$("#WAPlink").find("tr").each(function() {
		var a=$(this).find("td").eq(0).html();
		var b=$(this).find("td").eq(1).html();
		var c=$(this).find("td").eq(2).html();
		var reg1=new RegExp("([^\>]*"+a+"[^\<]*)","gm");
		switch(str){
			case "wap":
			var reg2='<a href="http://wap.wyn88.com/Hotel/HotelDetails?hotelcode='+b+'">$1</a>';
			break;
			default: 
			
			var reg2='<a href="http://www.wyn88.com/resv/hotel_'+c+'.html">$1</a>';

		}
		if(reg1.test(bdinner)){
			bdinner=bdinner.replace(reg1,reg2)
		}
	});
	//bdinner=bdinner.replace(/(\<a[^\>]+\>)|(\<\/a\>)/igm,"");
	bd.html(bdinner);
	
	}

$(".linkType a").eq(1).click(function(){
	ClearLink("wap");
	AddLink();
	})	
$(".linkType a").eq(0).click(function(){
	ClearLink("www");
	AddLink();
	})	
});
//对象继承
Object.extend = function(destination,source){
	for(var property in source){
		destination[property] = source[property]
	}
return destination;
}

