<?php
namespace Wyn\Controller;
use Think\Controller;
use Think\Page;
class AddLinkController extends Controller{
	public function index(){
		$this->display();
	}
	//获取所有酒店名及链接代码
	public function getHotelCode(){
		$type = $_POST['type'];
		//$type = 'hotelPmsCode';
		$h = M('hotel');
		$data = $h->field('hotelName,'.$type)->select();
		$this->ajaxReturn($data);
	}
	//搜索酒店名及代码
	public function searchHotel(){
		$keyword = $_POST['keyword'];
		if(!isset($keyword) || $keyword=="" || $keyword==null){
			$this->ajaxReturn(null);
		}
		//$keyword = '人民';
		$h = M('hotel');
		$map['hotelName'] = array('LIKE','%'.$keyword.'%');
		$data = $h->where($map)->field('hotelName,hotelPmsCode')->select();
		$this->ajaxReturn($data);
		//$this->ajaxReturn(array('var'=>$_POST['keyword']));
		
	}
}
