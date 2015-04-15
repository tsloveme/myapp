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
	public function getIpHotel(){
		$p = M('proxy');
		$ip = $p->select();
		$h = M('hotel');
		$hotel = $h->select();
		$this->ajaxReturn(array('ip'=>$ip,'hotel'=>$hotel));
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
	
}









	









