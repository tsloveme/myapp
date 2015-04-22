<?php
namespace Qnr\Controller;
use Think\Controller;
class PricedataController extends Controller {
    //首页
	public function index(){
		$tst = M('timestamp');
		$data = $tst->order('id desc')->limit(100)->select();
		$this->assign('selects',$data);
		$this->display();
    }
	public function search(){
		$timeid = $_POST['timeid'];
		$tst = M('timestamp');
		$data = $tst->order('id desc')->limit(100)->select();
		$this->assign('selects',$data);
		$data1 = $tst->where('id="'.$timeid.'"')->find();
		$this->assign('time',$data1['time']);
		$price = D('price');
		$data2 = $price->relation(true)->where('timestampid="'.$timeid.'"')->select();
		foreach($data2 as $k => $v){
			preg_match_all('/([a-z,A-Z_]+)_(\d+)/',$v['hotelseq'],$matchs);
			$data2[$k]['hotelseq'] = $matchs[1][0].'/dt-'.$matchs[2][0];
		}
		$this->assign('prices',$data2);
		$this->display('index');
	}
}









	









