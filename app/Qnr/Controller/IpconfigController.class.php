<?php
namespace Qnr\Controller;
use Think\Controller;
class IpconfigController extends Controller {
	public function index(){
		$this->display();
    }
	//获取代理ip地址
	public function getIpFromWeb(){
		$web = 'http://www.xici.net.co/';
		$str = file_get_contents($web);
		//return file_get_contents($web);
		//$ch = curl_init($web);
		$ptnNation = '/[\s\S]*国外高匿代理IP/i';
		if(preg_match_all($ptnNation,$str,$mArr)){
			$strIp = $mArr[0][0];
			$ptnIp = '/<td>(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})<\/td>[^\<]*<td>([\d]+)<\/td>[^\<]*<td>[\s\S]*?<\/td>[^\<]*<td>[\s\S]*?<\/td>[^\<]*<td>(\w+)<\/td>/i';
			
			if(preg_match_all($ptnIp,$strIp,$mip)){
				//dump($mip);
				$ips=$this->ValidateIP($mip);
				//dump($ips);
				$p = M('proxy');
				$p->where('1')->delete();
				$i = 0;
				while($i < count($ips)){
					$p->add(array(
					'ipAddress' => $ips[$i][0],
					'port' => $ips[$i][1],
					'protocol' => $ips[$i][2]
					));
					$i++;
				}
			}
		}
		$this->ajaxReturn(array('num'=>count($ips)));

	}
	//验证代理IP能否连接
	/*返回可以的代理服务器的地址和端口的二维地址。
	*传入二维数组对象
	*/
	protected function ValidateIP($arr){
		$testUrl='http://touch.qunar.com/h5/hotel/hotellist?city=%E5%B9%BF%E5%B7%9E&keywords=%E7%BB%B4%E4%B9%9F%E7%BA%B3';
		$mh = curl_multi_init();
		$chs = array();
		$arr_tem = array();
		$len = count($arr[1]);
		$ptnError = '/(<H2>您所请求的网址（URL）无法获取<\/H2>)|(请输入验证码以继续访问)/im';
		//$len = 5;
		for($i=0; $i<$len; $i++){
			$chs[$i] = curl_init();
			curl_setopt($chs[$i],CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
			curl_setopt($chs[$i],CURLOPT_URL,$testUrl);
			curl_setopt($chs[$i],CURLOPT_TIMEOUT,8);//设置超时
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
				//echo  '111-';
				array_push($arr_tem,array($arr[1][$k],$arr[2][$k],$arr[3][$k]));
			}		
			curl_multi_remove_handle($mh,$v);
			curl_close($v);
		}
		curl_multi_close($mh);
		return $arr_tem;		
	}
}



















