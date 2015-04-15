<?php
namespace Qnr\Controller;
use Think\Controller;
class GethotelController extends Controller {
	public function index(){
		$this->display();
    }
	//城市更新
	public function getCity(){
		header("Content-type:text/html;charset=utf-8");
		/*$url = 'http://touch.qunar.com/h5/hotel/hotelcitylist?_='.rand();
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$str = curl_exec($ch);
		curl_close($ch);
		$ptnPre = '/[\s\S]*?(?=东南亚)/m';
		if(preg_match_all($ptnPre,$str,$match)){
			$ptnCity = '/[\x{4e00}-\x{9fa5}]+/u';
			preg_match_all($ptnCity,$match[0][0],$citys);
		}
		$p = M('proxy');
		$ips = $p->getField('id',true);
		
		//$this->ajaxReturn(array('citys'=>array('广州','雅安','深圳'),'ipids'=>$ips));
		$this->ajaxReturn(array('citys'=>$citys[0],'ipids'=>$ips));*/
		$url = 'http://hotel.qunar.com/render/hoteldiv.jsp?&__jscallback=XQScript_20';
		$str = file_get_contents($url);
		$ptnPre = '/[\s\S]*?(?="新加坡")/im';
		$arrTem = array();
		if(preg_match_all($ptnPre,$str,$match)){
			$ptnCity = '/cityurl":"([a-z,A-Z,_]+)","name":"([\x{4e00}-\x{9fa5}]+)/u';
			preg_match_all($ptnCity,$match[0][0],$citys);
		}
		foreach($citys[1] as $k => $v){
			array_push($arrTem,array(
				'cityurl'=> $v,
				'name'=> $citys[2][$k]
			));
		}
		//$p = M('proxy');
		//$ips = $p->getField('id',true);
		//$this->ajaxReturn(array('citys'=>array('广州','雅安','深圳'),'ipids'=>$ips));
		//$this->ajaxReturn(array('citys'=>$arrTem,'ipids'=>$ips));
		$this->ajaxReturn(array('citys'=>$arrTem));
		
	}
	//城市验证存储
	public function store(){
		/*$ocity = $_POST['city'];
		$ipid = $_POST['ipid'];
		$city = urlencode($ocity);
		//$city = urlencode('阿坝');
		$keyword=urlencode('维也纳');
		$url = 'http://touch.qunar.com/h5/hotel/hotellist?city='.$city.'&keywords='.$keyword.'&page=1';
		$p = M('proxy');
		$ip = $p->find($ipid);
		$port = $ip["port"];
		$address = $ip['protocol'].'://'.$ip['ipaddress'];
		$ua = A('RandomUserAgent')->index();
		$ptnBlock = "/请输入验证码以继续访问/m";
		$ptnNoHave = '/该条件查询无结果，为您推荐如下酒店/';
		$ptnHave = '/<div class="info">[\s\S]*?<div class="title">[\s\S]*?维也纳[\s\S]*?<\/div>/im';
		$ptnCityUrl = '/name="cityUrl" value="([a-z,A-z]+)"/i';
		//echo($address.':'.$port);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT,$ua);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_PROXY,$address);
		curl_setopt($ch,CURLOPT_PROXYPORT,$port);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$str = curl_exec($ch);
		curl_close($ch);
		if($str && !preg_match($ptnBlock,$str) && !preg_match($ptnNoHave,$str) && preg_match($ptnHave,$str)){
			preg_match($ptnCityUrl,$str,$result);
			$c = M('city');
			if($result[1]){
				$data["cityName"]=$ocity;
				$data["cityUrl"]=$result[1];
				$have = $c->where($data)->select();
				if (!$have){
					$c->add($data);
				}
			}
		}
		$this->ajaxReturn(array(1));*/
		$cityName = $_POST['name'];
		$p_cityurl = $_POST['cityurl'];
		$url = "http://hotel.qunar.com/render/renderAPIList.jsp";
		$attrs = '?attrs=0FA456A3,L0F4L3C1,ZO1FcGJH,J6TkcChI,HCEm2cI6,08F7hM4i,8dksuR_,YRHLp-jc,pl6clDL0,HFn32cI6,vf_x4Gjt,2XkzJryU,vNfnYBK6,TDoolO-H,pk4QaDyF,x0oSHP6u,z4VVfNJo,5_VrVbqO,VAuXapLv,U1ur4rJN,px3FxFdF,xaSZV4wU,ZZY89LZZ,ZYCXZYHIRU,sYWEvpo,er8Eevr,HGYGeXFY,ownT_WG6,0Ie44fNU,yYdMIL83,MMObDrW4,dDjWmcqr,Y0LTFGFh,6X7_yoo3,8F2RFLSO,U3rHP23d,cGlja1Vw,7b4bfd15,yamiYIN,6bf51de0';
		$static = '&showAllCondition=1&showBrandInfo=1&showNonPrice=1&showFullRoom=1&showPromotion=1&showTopHotel=1&showGroupShop=1&output=json1.1';
		$v = '&v=0.03976182053056043';
		$requestTime = '&requestTime=1428042604729';
		$mixKey = '&mixKey=0b18d7101e6740d14284068129081cChUnYV9zguLOylfGbw12EiZzHu5D';
		$requestor = '&requestor=RT_HSLIST';
		$cityurl = '&cityurl='.$p_cityurl;
		$q = '&q=%E7%BB%B4%E4%B9%9F%E7%BA%B3';//维也纳 encodeURI
		$fromDate = '&fromDate='.date('Y-m-d');
		$toDate = '&toDate='.date('Y-m-d', time()+24*60*60);
		$limit = '&limit=0%2C15';
		$filterid = '&filterid=c5d94d16-eb97-4c6d-ab00-f0d6ef8afe7b_A';
		$jscallback = '&__jscallback=XQScript_6';
		$url = $url.$attrs.$static.$v.$requestTime.$mixKey.$requestor.$cityurl.$q.$fromDate.$toDate.$limit.$filterid.$jscallback;
		
		$str =  file_get_contents($url);
		if(preg_match('/"brand":"[^"]+","group":"维也纳酒店集团"/um',$str)){
			$c = M('city');
			$data["cityName"] = $cityName;
			$data["cityUrl"] = $p_cityurl;
			$have = $c->where($data)->select();
			if (!$have){
				$c->add($data);
			}
		}
		
		$this->ajaxReturn(array(1));
		
	}
	//获取酒店和代理ip
	public function getHotelByCity(){
		$c = M('city');
		$data = $c->field('cityurl,cityname')->select();
		//dump($citys);
		//exit;
		//$p = M('proxy');
		//$ips = $p->getField('id',true);
		//$this->ajaxReturn(array('citys'=>$citys,'ipids'=>$ips));
		//$this->ajaxReturn(array('citys'=>array('佛山','东莞'),'ipids'=>$ips));
		$this->ajaxReturn($data);
		
	}
	//酒店入库
	public function storeHotel(){
		/*$ocity = $_POST['city'];
		//$ocity = $_GET['city'];
		$c = M('city');
		$ipid = $_POST['ipid'];
		//$ipid = $_GET['ipid'];
		$condition = array('cityName'=>$ocity);
		$cid = $c->where($condition)->getField('id');
		$page = $_POST['page']? $_POST['page']:1;
		//$page = $_GET['page']? $_GET['page']:1;
		$city = urlencode($ocity);
		$keyword=urlencode('维也纳');
		$url = 'http://touch.qunar.com/h5/hotel/hotellist?city='.$city.'&keywords='.$keyword.'&page='.$page;
		$p = M('proxy');
		$ip = $p->find($ipid);
		$port = $ip["port"];
		$address = $ip['protocol'].'://'.$ip['ipaddress'];
		$ua = A('RandomUserAgent')->index();
		$ptnBlock = "/请输入验证码以继续访问/m";
		$ptnNoHave = '/该条件查询无结果，为您推荐如下酒店/m';
		$ptnHotelInfo = "/data-seq='([a-z,A-Z]+_\d+)'[\s\S]*? <div class=".'"title">[\s\S]*?([\x{4e00}-\x{9fa5}]+)/iu';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT,$ua);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_PROXY,$address);
		curl_setopt($ch,CURLOPT_PROXYPORT,$port);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$str = curl_exec($ch);
		curl_close($ch);
		if($str!=null && !preg_match($ptnBlock,$str) && !preg_match($ptnNoHave,$str)){
			preg_match_all($ptnHotelInfo,$str,$result);
			$hotel = M('hotel');
			foreach($result[1] as $k => $v){
				$data['cityId']=$cid;
				$data['hotelSeq']=$v;
				$data['hotelName']=$result[2][$k];
				if(strlen($data['hotelName'])>=14){
					$data['hotelName'] = $this->fullName($v);
				}
				$condiction = array('hotelSeq'=>$v,'hotelName'=>$data['hotelName'],'_logic'=>'OR');
				$have = $hotel->where($condiction)->select();
				if(!$have){
					$hotel->add($data);
				}
				
			}
			//if(preg_match('/该条件查询无结果，为您推荐如下酒店/',$str,$match)){
			//	//exit;
			//}
			//else{
			//	$this->ajaxReturn(array(1,'page'=>$page+1));
			//}
		$this->ajaxReturn(array(1,'page'=>$page+1));
		}
		$this->ajaxReturn(array(1));*/
		//$p_cityurl = 'shenzhen';
		$p_cityurl = $_POST['cityurl'];
		$p_page = $_POST['page'];
		//$p_cityurl = 'beijing_city';
		//$p_page = 1;
		$url = "http://hotel.qunar.com/render/renderAPIList.jsp";
		$attrs = '?attrs=0FA456A3,L0F4L3C1,ZO1FcGJH,J6TkcChI,HCEm2cI6,08F7hM4i,8dksuR_,YRHLp-jc,pl6clDL0,HFn32cI6,vf_x4Gjt,2XkzJryU,vNfnYBK6,TDoolO-H,pk4QaDyF,x0oSHP6u,z4VVfNJo,5_VrVbqO,VAuXapLv,U1ur4rJN,px3FxFdF,xaSZV4wU,ZZY89LZZ,ZYCXZYHIRU,sYWEvpo,er8Eevr,HGYGeXFY,ownT_WG6,0Ie44fNU,yYdMIL83,MMObDrW4,dDjWmcqr,Y0LTFGFh,6X7_yoo3,8F2RFLSO,U3rHP23d,cGlja1Vw,7b4bfd15,yamiYIN,6bf51de0';
		$static = '&showAllCondition=1&showBrandInfo=1&showNonPrice=1&showFullRoom=1&showPromotion=1&showTopHotel=1&showGroupShop=1&output=json1.1';
		$v = '&v=0.1576704292083123';
		$requestTime = '&requestTime='.time().'025';
		$mixKey = '&mixKey=0fe667d8bf2b679030d142804260228919xYhOcUjaslwO2iLyfGuZugW0R';
		$requestor = '&requestor=RT_HSLIST';
		$cityurl = '&cityurl='.$p_cityurl;
		$q = '&q=%E7%BB%B4%E4%B9%9F%E7%BA%B3';//维也纳 encodeURI
		$fromDate = '&fromDate='.date('Y-m-d');
		$toDate = '&toDate='.date('Y-m-d', time()+24*60*60);
		$limit = '&limit='. 15*($p_page-1) .'%2C' . 15;
		$filterid = '&filterid=67539127-4892-48f7-a2df-ab577e478579_A';
		$jscallback = '&__jscallback=XQScript_5';
		$url = $url.$attrs.$static.$v.$requestTime.$mixKey.$requestor.$cityurl.$q.$fromDate.$toDate.$limit.$filterid.$jscallback;
		$str = file_get_contents($url);
		//echo($str);
		$ptnG = '/\{"id":[\s\S]*?\}\}/i';
		//$ptnhotel = '/hotelName":"([^\"]*)"[\s\S]*?seq":"([^\"]*)"[\s\S]*?brand":"[^\"]*","group":"维也纳酒店集团/'; 
		$ptnhotel = '/{"id":"([^\"]*)"[\s\S]*?hotelName":"([^\"]*)"[\s\S]*?"group":"维也纳酒店集团/'; 
		if(preg_match_all($ptnG,$str,$maches))
		{	
			$c = M('city');
			$condition = array('cityUrl'=>$p_cityurl);
			$cid = $c->where($condition)->getField('id');
			$hotel = M('hotel');
			//echo($str);
			foreach($maches[0] as $kk => $vv ){
				if(preg_match($ptnhotel,$vv,$match)){
					/*dump($match[1]);
					dump($match[2]);*/
					$data['cityId']=$cid;
					$data['hotelSeq']= $match[1];
					$data['hotelName'] = $match[2];
					$condiction = array('hotelSeq'=>$data['hotelSeq'],'hotelName'=>$data['hotelName'],'_logic'=>'OR');
					$have = $hotel->where($condiction)->select();
					if(!$have){
						$hotel->add($data);
					}
				}
			}
			$this->ajaxReturn(array(1,'page'=>$p_page+1));
			//dump($maches);
		}
		$this->ajaxReturn(array(1));
		
		
	}
	
	//补全酒店名
	public function fullName($seq){
		//$seq = 'shenzhen_2158';
		preg_match('/([a-z,A-Z,_]+)_(\d+)/',$seq,$result);
		$hotelId = $result[2];
		$city = $result[1];
		$ua ='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)';
		$url = 'http://hotel.qunar.com/city/'.$city.'/dt-'.$hotelId.'/';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_USERAGENT,$ua);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$str = curl_exec($ch);
		curl_close($ch);
		preg_match('/hotelName="([\x{4e00}-\x{9fa5}]+)"/u',$str,$title);
		if($title){
			return $title[1] ;
			//echo $title[1] ;
		}
		else{
			return '网络错误没有找到';
			//echo '网络错误没有找到';
		}
	}
	public function fullName1($seq){
		//$seq = 'shenzhen_2158';
		preg_match('/([a-z,A-Z]+)_(\d+)/',$seq,$result);
		$hotelId = $result[2];
		$city = $result[1];
		$ua ='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)';
		$url = 'http://hotel.qunar.com/city/'.$city.'/dt-'.$hotelId.'/';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_USERAGENT,$ua);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$str = curl_exec($ch);
		curl_close($ch);
		//dump($str);
		preg_match('/hotelName="([\s\S]+?)"/',$str,$title);
		if($title){
			//return $title[1] ;
			echo $title[1] ;
		}
		else{
			//return '网络错误没有找到';
			echo '网络错误没有找到';
		}
	}
	
		public function store1(){
		//$ocity = $_POST['city'];
		//$ipid = $_POST['ipid'];
		$ocity = '深圳';
		$ipid =  1323;
		$city = urlencode($ocity);
		//$city = urlencode('阿坝');
		$keyword=urlencode('维也纳');
		$url = 'http://touch.qunar.com/h5/hotel/hotellist?city='.$city.'&keywords='.$keyword.'&page=1';
		$p = M('proxy');
		$ip = $p->find($ipid);
		$port = $ip["port"];
		$address = $ip['protocol'].'://'.$ip['ipaddress'];
		$ua = A('RandomUserAgent')->index();
		$ptnBlock = "/请输入验证码以继续访问/m";
		$ptnNoHave = '/该条件查询无结果，为您推荐如下酒店/';
		$ptnHave = '/<div class="info">[\s\S]*?<div class="title">[\s\S]*?维也纳[\s\S]*?<\/div>/im';
		$ptnCityUrl = '/name="cityUrl" value="([a-z,A-z]+)"/i';
		//echo($address.':'.$port);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT,$ua);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_PROXY,$address);
		curl_setopt($ch,CURLOPT_PROXYPORT,$port);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$str = curl_exec($ch);
		curl_close($ch);
		if($str && !preg_match($ptnBlock,$str) && !preg_match($ptnNoHave,$str) && preg_match($ptnHave,$str)){
			preg_match($ptnCityUrl,$str,$result);
			$c = M('city');
			if($result[1]){
				$data["cityName"]=$ocity;
				$data["cityUrl"]=$result[1];
				$have = $c->where($data)->select();
				if (!$have){
					$c->add($data);
				}
			}
		}
		$this->ajaxReturn(array(1));
	}

}



















