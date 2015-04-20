<?php
namespace Qnr\Controller;
use Think\Controller;
class IndexController extends Controller {
    //首页
	public function index(){
		$this->display();
    }

	/*获取代理ip地址和hotel信息；
	*返回{'ip':[23,24,25,26......],"hotel":{"id":"907","hotelname":"维也纳酒店深圳福田八卦路店", 	"hotelSeq":"shenzhen_10322....."}}
	*		
	*
	*
	*/
	/*public function getIpHotel(){
		$p = M('proxy');
		$ip = $p->select();
		$h = M('hotel');
		$hotel = $h->select();
		$this->ajaxReturn(array('ip'=>$ip,'hotel'=>$hotel));
	}*/
	public function getHotel(){
		$p = M('proxy');
		$ips = $p->select();
		$h = M('hotel');
		$hotel = $h->select();
		$time = M('timestamp');
		$timeid = $time->add(array('time'=>null));
		$this->ajaxReturn(array('hotel'=>$hotel,timeId=>$timeid,'ips'=>$ips));
		
	}
	public function priceUnitInit(){
		/*$protocol= $_POST['protocol'];
		$ip = $_POST['ip'];
		$port = $_POST['port'];
		$hotelid = $_POST['id'];
		$seq = $_POST['seq'];
		//测试数据
		$protocol= 'HTTP';
		$ip = '202.108.50.75';
		$port = '80';
		$hotelid = '902';
		$seq = 'shenzhen_8524';
		$hotelid = '902';*/
		$seq = $_POST['seq'];
		//$seq = 'shenzhen_8524';

		$url = 'http://touch.qunar.com/h5/hotel/hoteldetail?seq='.$seq;
		/*$ua = A('RandomUserAgent')->index();
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT,$ua);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_PROXY,$ip);
		curl_setopt($ch,CURLOPT_PROXYPORT,$port);
		//curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$str = curl_exec($ch);
		curl_close($ch);*/
		$str = file_get_contents($url);
		$ptnRoomType = '/<div\sdata-room="([^"]*)"/';
		if(preg_match_all($ptnRoomType,$str,$matches)){
			$room = M('room');
			foreach($matches[1] as $k => $v){
				$condiction = 'roomName="'.$v.'"';
				//$condiction = array('roomName'=>$v);
				$result = $room->where($condiction)->select();
				if(!$result){
					$room->add(array(
						'roomName' => $v
					));
				}
			}
			$map['roomName'] = array('in',$matches[1]);
			$result = $room->where($map)->field('id,roomName')->select();
			$this->ajaxReturn($result);
		}
		$this->ajaxReturn(array('roomnum'=> 0));
		
	}
	//价格爬虫单元
	public function priceRobot(){
		//seq
		$seq = $_POST['seq'];
		//ip资源
		$ips = $_POST['ips'];
		$ips = '&id:1421HTTP://202.108.50.75:80&id:1421HTTP://202.108.50.75:80';
		if(preg_match_all('/&id:(\d+)([^&]+)/i',$ips,$matchs)){
			$ips = array();
			foreach($matchs[1] as $k => $v){
				array_push($ips,array('id'=>$v,'ip'=>$matchs[2][$k]));
			}
		}
		//fx资源
		$roomtypes = $_POST['roomtypes'];
		$roomtypes ='{"id":"8","roomname":"\u8c6a\u534e\u53cc\u4eba\u623f"},{"id":"9","roomname":"\u8c6a\u534e\u5355\u4eba\u623f"}';
		if(preg_match_all('/{"id":"([^"]+)","roomname":"([^"]+)"}/i',$roomtypes,$matchs)){
			$roomtypes = array();
			foreach($matchs[1] as $k => $v){
				array_push($roomtypes,array('id'=>$v,'roomname'=>$matchs[2][$k]));
			}
		}
		//var_dump($ips);
		//var_dump($roomtypes);
		//curl多线程抓取
		$mh = curl_multi_init();
		$chs = array();
		$arr_tem = array();
		$len = count($arr[1]);
		$ptnError = '/(<H2>您所请求的网址（URL）无法获取<\/H2>)|(请输入验证码以继续访问)/im';
		$ptnPrice = '//';
		$ua = A('RandomUserAgent');
		foreach($roomtypes as $k => $v){
			$chs[$k] = curl_init();
			curl_setopt($chs[$k],CURLOPT_USERAGENT,$ua->index());
			curl_setopt($chs[$k],CURLOPT_URL,'http://touch.qunar.com/h5/hotel/hotelprice?seq='.$seq.'&tpl=hotel.hotelPriceTpl&room='.urlencode($v['roomname']));
			curl_setopt($chs[$k],CURLOPT_TIMEOUT,8);
			curl_setopt($chs[$k],CURLOPT_PROXY,$ips[$k]['ip']);
			curl_setopt($chs[$k], CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($chs[$k], CURLOPT_RETURNTRANSFER,1);
			curl_multi_add_handle($mh,$chs[$k]);
		}
		//cpu占用率高解决死循环优化
		do {
			$mrc = curl_multi_exec($mh,$active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		while ($active and $mrc == CURLM_OK) {
			if (curl_multi_select($mh) != -1) {
				do {
					$mrc = curl_multi_exec($mh, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		
		foreach($chs as $k => $v){
			$result = curl_multi_getcontent($v);
			if($result && !preg_match($ptnError,$result)){
				array_push($arr_tem,array($arr[1][$k],$arr[2][$k],$arr[3][$k]));
			}		
			curl_multi_remove_handle($mh,$v);
			curl_close($v);
		}
		curl_multi_close($mh);
		return $arr_tem;
		
		/*for($i=0; $i<$len; $i++){
			$chs[$i] = curl_init();
			curl_setopt($chs[$i],CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
			curl_setopt($chs[$i],CURLOPT_URL,$testUrl);
			//设置超时
			curl_setopt($chs[$i],CURLOPT_PROXY, strtolower($arr[3][$i])=="http"? $arr[1][$i] : $arr[3][$i].$arr[1][$i]);//ip地址包含协议判断
			curl_setopt($chs[$i],CURLOPT_PROXYPORT,$arr[2][$i]);//端口
			curl_setopt($chs[$i], CURLOPT_FOLLOWLOCATION,1);//抓取跳转后的页面。
			//curl_setopt($chs[$i], CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
			curl_setopt($chs[$i], CURLOPT_RETURNTRANSFER, 1);//返回字符串
			curl_multi_add_handle($mh,$chs[$i]);
		}
		do {
			$mrc = curl_multi_exec($mh,$active);
			} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		while ($active and $mrc == CURLM_OK) {
			if (curl_multi_select($mh) != -1) {
				do {
					$mrc = curl_multi_exec($mh, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
        }
		foreach($chs as $k => $v){
			$result = curl_multi_getcontent($v);
			if($result && !preg_match($ptnError,$result)){
				echo  '111-';
				array_push($arr_tem,array($arr[1][$k],$arr[2][$k],$arr[3][$k]));
			}		
			curl_multi_remove_handle($mh,$v);
			curl_close($v);
		}
		curl_multi_close($mh);
		return $arr_tem;	*/	

		$this->ajaxReturn(array(1));
		
	}
	public function storePrice(){
		$timeid = $_POST['timeid'];
		$hotelSeq = $_POST['seq'];
		$priceData = $_POST['pricedata'];
		
		/*$timeid = 26;
		$hotelSeq = 'shenzhen_8524';
		$priceData = '","roomType":"标准单人房(无窗)","priceQnr":"235","priceWyn":"245","roomType":"高级房","priceQnr":"270","priceWyn":"1","roomType":"豪华单人房","priceQnr":"287","priceWyn":"355","roomType":"豪华双人房","priceQnr":"287","priceWyn":"355","roomType":"商务数码房","priceQnr":"320","priceWyn":"308","roomType":"家庭景观房","priceQnr":"321","priceWyn":"372","roomType":"商务单人房","priceQnr":"322","priceWyn":"372","roomType":"豪华套房","priceQnr":"389","priceWyn":"440","roomType":"豪华商务单人房","priceQnr":"1","priceWyn":"398"';*/
		
		$h = M("hotel");
		$hotelid = $h->field('id')->where('hotelSeq = "'.$hotelSeq.'"')->find();
		$hotelid = $hotelid['id'];
		$ptnPrice = '/"roomType":"([^"]+)","priceQnr":"([^"]+)","priceWyn":"([^"]+)"/';
		$dataList = array();
		$price = M('price');
		if(preg_match_all($ptnPrice,$priceData,$matchs)){
			//dump($matchs);
			$roomtypeIds = $this->handleRoomtype($matchs[1]);
			//dump($roomtypeIds);
			foreach($matchs[2] as $k => $v){
				/*array_push($dataList,array(
				'roomTypeId' => $roomtypeIds[$k],
				'hotelId' => $hotelid,
				'priceQnr' => $v,
				'priceWyn' => $matchs[3][$k],
				'timeStampId' => $timeid
				));*/
				$price->add(array(
				'roomTypeId' => $roomtypeIds[$k],
				'hotelId' => $hotelid,
				'priceQnr' => $v,
				'priceWyn' => $matchs[3][$k],
				'timeStampId' => $timeid
				));
			}
			//$price->addAll($dataList);
			
		}
		$this->ajaxReturn(array(1));
	}
	private function handleRoomtype($arr){
		$room = M('room');
		$reArr = array();
		foreach($arr as $k => $v){
			$condiction = 'roomName="'.$v.'"';
			$result = $room->where($condiction)->field('id')->select();
			if(!$result){
				$result = $room->add(array(
					'roomName' => $v
				));
			}
			else{
				$result = $result[0]['id'];
			}
			array_push($reArr,$result);
		}
		return $reArr;
	}
	public function GetTop(){

		$price = D("price");
		//$price->alert();
		//$data = $price->relation(true)->limit(10)->select();
		//$data = $price->relation(true)->find(100);
		//$data = $price->limit(10)->select();
		$data = $price->where('timeStampId=35')->limit(10)->relation(true)->select();
		dump($data);
	}
}









	









