<?php
namespace Weixin\Controller;
use Think\Controller;
class IndexController extends Controller{
	
	//首页
	public function index(){
		$this->display();
	}

	//用户添加
	public function signAdd(){
        $openid = $_POST['openid'];
        $newid = $_POST['newid'];
        //$openid = 'orcd1jm6-WpiDNRoSmTRApIiwBjU';
        //$newid = '7ea5be3d-8cf6-4ac8-b435-c91d6dda258e';
        if(!($openid && $newid)){
            $this->show('failed');
            exit;
        }
        $sign = M('Sign');
        $condition= array('openid'=>$openid);
        $flag = $sign->where($condition)->select();
        if(!$flag){
            $sign->add(array(
                'openid'=>$openid,
                'newid'=>$newid
            ));
            $this->show('ok');
            exit;
        }
        else{
            $this->show('failed');
            exit;
        }
	}
	//添加用户接口
	public function runAdd(){
	    //$url = 'http://10.8.25.25/index.php/weixin/index/signAdd';
	    $url = 'http://www.uinote.cn/myapp/index.php/weixin/index/signAdd';
        $data = array(
            'openid' => 'orcd1jm6-WpiDNRoSmTRApIiwBjU',
            'newid' => '7ea5be3d-8cf6-4ac8-b435-c91d6dda258e'
        );
        //var_dump($data);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec($ch);
        curl_close($ch);
        echo '返回值'.$return;
	}
    //签到动作
    public function runSign(){
        set_time_limit(60);
        $sign = M('Sign');
        $data = $sign->select();
        //dump($data);
        foreach($data as $k1 => $v1){
            $url = 'http://wynwx.wyn88.com/tools/hotel_sign_ajax.ashx?action=signin';
            $url .='&asscekey='.$this->randStr();
            $url .='&openid='.$v1['openid'];
            $url .='&activiteid=9';
            $ch = curl_init();
            //dump($url);
            //continue;
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            print_r($output);
        }
    }
    //随机串
    private function randStr($length = 43){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
