<?php
namespace Qnr\Controller;
use Think\Controller;
class AgentpageController extends Controller {
    //代理访问页
	public function index(){
		header("Content-type: text/html; charset=utf-8");
		$seq = $_GET['seq'];
		$ptn = '/([a-z,A-Z_]*)_([\d]+)/';
		if(preg_match_all($ptn,$seq,$matchs)){
			$url ='http://hotel.qunar.com/city/'.$matchs[1][0].'/dt-'.$matchs[2][0];
			//echo($url);
			$str = file_get_contents($url);
			$str = preg_replace('/"\/render\//','"http://hotel.qunar.com/render/',$str);
			$this->assign('html',$str);
			$this->display();
		}
		
		
    }
	
}