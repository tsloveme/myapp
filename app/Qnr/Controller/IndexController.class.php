<?php
namespace Qnr\Controller;
use Think\Controller;
class IndexController extends Controller {
    //首页
	public function index(){
		$this->display();
    }

	public function getIpFromWeb(){
		
		//首页获取代理
		$web = 'http://www.xici.net.co/';
		$str = file_get_contents($web);
		//return file_get_contents($web);
		//$ch = curl_init($web);
		$ptnNation = '/[\s\S]*国外高匿代理IP/i';
		if(preg_match_all($ptnNation,$str,$mArr)){
			$strIp = $mArr[0][0];
			$ptnIp = '/<td>(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})<\/td>[^\<]*<td>([\d]+)<\/td>[^\<]*<td>[\s\S]*?<\/td>[^\<]*<td>[\s\S]*?<\/td>[^\<]*<td>(\w+)<\/td>/i';
			
			if(preg_match_all($ptnIp,$strIp,$mip)){
				dump($mip);
				$ips=$this->ValidateIP($mip);
				dump($ips);
				exit;
				$p = M('proxy');
				$p->where('1')->delete();
				$i = 0;
				while($i < count($mip[1])){
					$p->add(array(
					'ipAddress' => $mip[1][$i],
					'port' => $mip[2][$i],
					'protocol' => $mip[3][$i]
					));
					$i++;
				}
				$this->ajaxReturn(array('num'=>count($mip[1])));
				
			}
			
		}
		
	}
	//验证代理IP能否连接
	/*返回可以的代理服务器的地址和端口的二维地址。
	*传入二维数组对象
	*/
	protected function ValidateIP($arr){
		//$testUrl='http://libs.baidu.com/zepto/0.8/zepto.min.js';
		$testUrl='http://1111.ip138.com/ic.asp';
		$mh = curl_multi_init();
		$chs = array();
		$arr_tem = array();
		//$len = count($arr[1]);
		$len = 10;
		for($i=0; $i<$len; $i++){
			$chs[$i] = curl_init();
			curl_setopt($chs[$i],CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
			curl_setopt($chs[$i],CURLOPT_URL,$testUrl);
			curl_setopt($chs[$i],CURLOPT_TIMEOUT,1);
			curl_setopt($chs[$i],CURLOPT_PROXY,$arr[1][$i]);//ip地址
			curl_setopt($chs[$i],CURLOPT_PROXYPORT,$arr[2][$i]);//端口
			
			//curl_setopt($chs[$i], CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
			curl_setopt($chs[$i], CURLOPT_RETURNTRANSFER, 1);//返回字符串
			/*$file = fopen('E:\cookie\\'.$i.'txt',"a");
			fwrite($file,"");
			fclose($file);
			curl_setopt ($chs[$i], CURLOPT_COOKIEJAR, 'E:\cookie\\'.$i.'.txt');
			curl_setopt ($chs[$i], CURLOPT_HEADER, 1);
			curl_setopt ($chs[$i], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($chs[$i], CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($chs[$i], CURLOPT_TIMEOUT, 2);*/
			curl_multi_add_handle($mh,$chs[$i]);
		}
		do{
			curl_multi_exec($mh,$active);
		}while($active);
		foreach($chs as $k => $v){
			$result = curl_multi_getcontent($v);
			//echo $result;
			//dump($result);
			if($result){
				echo $result;
				array_push($arr_tem,array($arr[1][$k],$arr[2][$k],$arr[3][$k]));
			}		
			curl_multi_remove_handle($mh,$v);
			curl_close($v);
		}
		curl_multi_close($mh);
		
		return $arr_tem;		
	}
	//多线程demo抓取百度首页并写入文件
	public function multiThreadTest(){
		$testUrl = 'http://www.baidu.com/s?wd=';
		//$testUrl = 'http://www.dgs6.com/?wd=';
		$mh = curl_multi_init();
		$chs = array();
		for($i=0;$i<20;$i++){
			$chs[$i] = curl_init();
			curl_setopt($chs[$i],CURLOPT_URL,$testUrl.''.rand());
			curl_setopt($chs[$i],CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
			curl_setopt($chs[$i],CURLOPT_HEADER,true);
			curl_setopt($chs[$i],CURLOPT_CONNECTTIMEOUT,60);
			curl_setopt($chs[$i],CURLOPT_RETURNTRANSFER,true);
			curl_multi_add_handle($mh,$chs[$i]);
			
		}
		do {  
		  curl_multi_exec($mh,$active);  
		} while ($active);   
		foreach($chs as $k => $v){
			$data = curl_multi_getcontent($v);
			$fp = fopen('E:/phpweb/txt/Mutil/'.$k.'-'.microtime().'.txt','w+');
			fwrite($fp,$data);
			fclose($fp);
			curl_multi_remove_handle($mh,$v);
			curl_close($v);
							
		}
		/*foreach($chs as $k => $v){
			curl_multi_remove_handle($mh,$v);
			curl_close($v);
							
		}*/
		curl_multi_close($mh);  
	}
	//单线程测试
	public function sigleThread(){
		set_time_limit(30);
		$ch = curl_init();
		$dir = 'E:/phpweb/txt/Sigle/';
		$url = 'http://www.baidu.com/s?wd=';
		for($i=0; $i<20; $i++){
			curl_setopt($ch,CURLOPT_URL,$url.''.rand());
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
			curl_setopt($ch,CURLOPT_HEADER,true);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$str = curl_exec($ch);
			$fp = fopen($dir.$i.'-'.microtime().'.txt',"w+");
			fwrite($fp,$str);
			fclose($fp);
			
		}
		curl_close($ch);
	}
	//多线程没试2
	public function mutil(){
		$urls = array(  
		 'http://www.sina.com.cn/',  
		 'http://www.sohu.com/',  
		 'http://www.163.com/' 
		);  
		   
		$save_to='E:/phpweb/txt/test.txt';   // 把抓取的代码写入该文件  
		$st = fopen($save_to,"a");  
		   
		$mh = curl_multi_init();  
		foreach ($urls as $i => $url) {  
		  $conn[$i] = curl_init($url);  
		  curl_setopt($conn[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");  
		  curl_setopt($conn[$i], CURLOPT_HEADER ,0);  
		  curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT,60);  
		  curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER,true);  // 设置不将爬取代码写到浏览器，而是转化为字符串  
		  curl_multi_add_handle ($mh,$conn[$i]);  
		}  
		   
		do {  
		  curl_multi_exec($mh,$active);  
		} while ($active);  
			 
		foreach ($urls as $i => $url) {  
		  $data = curl_multi_getcontent($conn[$i]); // 获得爬取的代码字符串  
		  fwrite($st,$data);  // 将字符串写入文件。当然，也可以不写入文件，比如存入数据库  
		} // 获得数据变量，并写入文件  
		   
		foreach ($urls as $i => $url) {  
		  curl_multi_remove_handle($mh,$conn[$i]);  
		  curl_close($conn[$i]);  
		}  
		   
		curl_multi_close($mh);  
		fclose($st);  		
	}

	private $userAgent = array(
	'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5',
	
	'Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5',
	
	'Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
	
	'MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',//Android QQ浏览器 For android
	
	'Opera/9.80 (Android 2.3.4; Linux; Opera Mobi/build-1107180945; U; en-GB) Presto/2.8.149 Version/11.10',//Android Opera Mobile
	
	'Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13',//Android Pad Moto Xoom
	
	'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.337 Mobile Safari/534.1+',//BlackBerry
	
	'Mozilla/5.0 (SymbianOS/9.4; Series60/5.0 NokiaN97-1/20.0.019; Profile/MIDP-2.1 Configuration/CLDC-1.1) AppleWebKit/525 (KHTML, like Gecko) BrowserNG/7.1.18124',//Nokia N97
	
	'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; HTC; Titan)',//Windows Phone Mango
	
	'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:36.0) Gecko/20100101 Firefox/36.0',//FF 36
	
	'Mozilla/5.0 (Windows NT 5.0; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0',//FF 24
	
	'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_2_1 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5',//iphone 3gs
	
	'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5',//iphone4
	
	'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53',//5
	
	'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4',//6
	
	'Mozilla/5.0 (Linux; U; Android 2.2; en-us; Sprint APA9292KT Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',//htc evo
	
	'Mozilla/5.0 (Linux; Android 4.0.3; HTC One X Build/IML74K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19',//htc one x
	
	'Mozilla/5.0 (Linux; U; Android 4.0.3; en-us; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',//LG EVO 3G
	
	'Mozilla/5.0 (Linux; Android 4.0; LG-E975 Build/IMM76L) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19', //LG Optinims g
	
	'Mozilla/5.0 (Linux; U; Android 2.3; en-us; SAMSUNG-SGH-I717 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', //note1
	
	'Mozilla/5.0 (Linux; U; Android 4.1; en-us; GT-N7100 Build/JRO03C) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',//note2
	
	'Mozilla/5.0 (Linux; U; Android 4.3; en-us; SM-N900T Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',//note3
	
	'Mozilla/5.0 (Linux; Android 4.2.2; GT-I9505 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.59 Mobile Safari/537.36'//S4

	);
}



















