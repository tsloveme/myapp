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
	public function vld(){
		
		
	}
	
}