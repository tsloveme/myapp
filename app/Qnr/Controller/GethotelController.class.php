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
		$url = 'http://touch.qunar.com/h5/hotel/hotelcitylist?_='.rand();
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
		$this->ajaxReturn(array('citys'=>$citys[0],'ipids'=>$ips));
	}
	//城市验证存储
	public function store(){
		$ocity = $_POST['city'];
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
		$this->ajaxReturn(array(1));
	}
	//获取酒店和代理ip
	public function getHotelByCity(){
		$c = M('city');
		$citys = $c->getField('cityname',true);
		$p = M('proxy');
		$ips = $p->getField('id',true);
		$this->ajaxReturn(array('citys'=>$citys,'ipids'=>$ips));
		//$this->ajaxReturn(array('citys'=>array('佛山','东莞'),'ipids'=>$ips));
	}
	//酒店入库
	public function storeHotel(){
		$ocity = $_POST['city'];
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
			/*if(preg_match('/该条件查询无结果，为您推荐如下酒店/',$str,$match)){
				//exit;
			}
			else{
				$this->ajaxReturn(array(1,'page'=>$page+1));
			}*/
		$this->ajaxReturn(array(1,'page'=>$page+1));
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



















