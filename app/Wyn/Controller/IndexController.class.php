<?php
namespace Wyn\Controller;
use Think\Controller;
use Think\Page;
class IndexController extends Controller{
	public function index(){
		$this->display();
	}
	public function datasync(){
		$this->display();
	}
	
	//城市省份插入更新方法
	public function proviceInsert(){
		header("Content-type: text/html; charset=utf-8"); //声明页面UTF-8;防止乱码
		/*$provinceName=$_POST['provinceName'];
		$provinceNo=$_POST['provinceNo'];
		$m = M('province');
		$arr['provinceName']=$provinceName;
		if($m->where($arr)->find()){
			return 0;
		}
		$data['provinceName'] = $provinceName;
		$data['provinceNo'] = $provinceNo;
		$m->add($data);*/
		//空表一次性插入完成数据，去重。
		$p = M('province');
		$str= file_get_contents('http://www.wyn88.com/area/fetchCitiesBy1stLetterJson.html?firstLetters=abcdefghijklmnopqrstuvwxyz');
		//$str = mb_convert_encoding($str,'UTF-8','auto');//乱码转码
		$ptn = "/{\"provinceName\"[^}]+}/";
		preg_match_all($ptn, $str, $matches);
		$matches = $matches[0];
		$arr = array();
		foreach($matches as $key=>$value){
			preg_match("/provinceName\":\"\s*([^\s^\"]+)\s*\"/",$value,$mProvinceName);
			preg_match("/provinceNo\":\"\s*([^\s^\"]+)\s*\"/",$value,$mProvinceNo);
			$mProvinceName = $mProvinceName[1];
			$mProvinceNo = $mProvinceNo[1];
			array_push($arr,array('provinceName'=>$mProvinceName,'provinceNo'=>$mProvinceNo));		
		}
		//二维数组去重
		function array_unique_fb($array2D){
			foreach ($array2D as $k=>$v)
			{
			$v = join(",",$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
			$temp[$k] = $v;
			}
			$temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
			foreach ($temp as $k => $v)
			{
			$array=explode(",",$v); //再将拆开的数组重新组装
			$temp2[$k]["provinceName"] =$array[0];
			$temp2[$k]["provinceNo"] =$array[1];
			}
			return $temp2;
		}
		$arr = array_unique_fb($arr);
		//循环插入数据库
		$updateNum = $addNum =0;
		foreach($arr as $k => $v){
			$data["provinceName"]=$v["provinceName"];
			$data["provinceNo"]=$v["provinceNo"];
			unset($data["_logic"]);
			$resultSame = $p->where($data)->find();
			if($resultSame){
				continue;				
			}
			$data["_logic"] = 'XOR';
			$result = $p->where($data)->find();
			if($result){
				$id = $p->where($data)->getField('provinceId');				
				unset($data["_logic"]);
				$updatR  = $p->where('provinceId ='.$id)->save($data);
				if(!$updatR){
				array_push($ErrorArr,$data);//记录更新失败的记录
				}
				else{
					$updateNum+=1;
				}
			}			
			else{
				$resultA = $p->add($data);
				if(!$resultA){
				array_push($ErrorArr,$data);//记录新增失败的记录	
				}
				else{
					$addNum+=1;
				}
			}

		}
		$info = array("num"=>count($arr),/*'errlist'=>$ErrorArr,*/'addNum'=>$addNum,'updateNum'=>$updateNum);
		$this->ajaxReturn($info);
		//dump($arr);
		//$this->display();
	}
	public function updateCity(){//城市更新插入
		header("Content-type: text/html; charset=utf-8"); //声明页面UTF-8;防止乱码
		$c = M('city');
		$p = M('province');
		$str= file_get_contents('http://www.wyn88.com/area/fetchCitiesBy1stLetterJson.html?firstLetters=abcdefghijklmnopqrstuvwxyz');
		$ptn = "/{\"provinceName\"[^}]+}/";
		preg_match_all($ptn, $str, $matches);
		$matches = $matches[0];
		$arr = array();
		foreach($matches as $key=>$value){
			preg_match("/provinceName\":\"\s*([^\s^\"]+)\s*\"/",$value,$mProvinceName);
			preg_match("/cityName\":\"\s*([^\s^\"]+)\s*\"/",$value,$mcityName);
			preg_match("/cityNo\":\"\s*([^\s^\"]+)\s*\"/",$value,$mcityNo);
			
			$mProvinceName = $mProvinceName[1];
			$mcityName = $mcityName[1];
			$mcityNo = $mcityNo[1];
			$mprovinceId = $p->where('provinceName = "'.$mProvinceName.'"')->getField('provinceId');
			if(!$mprovinceId){continue;}
			array_push($arr,array(
			//'provinceName'=>$mProvinceName,
			'cityName'=>$mcityName,
			'cityNo'=>$mcityNo,
			'provinceid'=>$mprovinceId
			)
			);
			unset($mprovinceId);
		}
		
		//dump($arr);
		$dataRetrun=array("num"=>0);
		foreach($arr as $k=>$v){
			$condiction['cityName'] = $v['cityName'];
			$condiction['cityNo'] = $v['cityNo'];
			//dump($condiction);
			$result = $c->where($condiction)->find();
			if($result){continue;}
			else{
				$c->add($v);
				$dataRetrun['num']++;
			}
		}
		//dump($dataRetrun);
		$this->ajaxReturn($dataRetrun);
	}
	//http://www.wyn88.com/resv/city_4403.html?cityName=深圳市&pageNo=1
	public function getcity(){
		//header("Content-type: text/html; charset=utf-8");
		$c = M('city');
		$cityData=$c->getField('cityId,cityName,cityNo');
		$this->ajaxReturn($cityData);
	}
    public function updateHotel(){//酒店信息更新
        /*$cityId = 17;
        $cityNo = '4401';
        $cityName = '广州市';*/
        $cityId = $_POST['cityId'];
        $cityNo = $_POST['cityNo'];
        $cityName = $_POST['cityName'];
        $pageI = $_POST['pageI'];
        $str = file_get_contents('http://www.wyn88.com/resv/city_'.$cityNo.'.html?cityName='.$cityName);
        $ptn = '/">(\d+)<\/a>[\s\n]*<span class=\'left\'>/';
        preg_match_all($ptn, $str, $matches);
        $pageQuantity = intval($matches[1][0]);//匹配pageNo页数
        $pageQuantity = $pageQuantity ? $pageQuantity : 1;
        $h = M('hotel');
        $addNum=0;//更新计数器
        //页码循环
        $strPage = file_get_contents('http://www.wyn88.com/resv/city_'.$cityNo.'.html?cityName='.$cityName.'&pageNo='.$pageI);
        $ptnhotel = '/<a href = "[^\"]*_(\d+)\.html" target = "_blank">([^\<]*)<\/a>/';
        preg_match_all($ptnhotel, $strPage,$mhotel);
        //页内遍历
        for($i=0;$i<count($mhotel[0]);$i++){
            $data = array(
                'hotelName' => $mhotel[2][$i],
                'cityid' => $cityId,
                'hotelPmsCode' => $mhotel[1][$i]
            );
            $ifHave = $h->where($data)->find();
            if($ifHave){
                continue;
            }
            else{
                $addNum+=1;
                $h->add($data);
            }
        }
        //$this->ajaxReturn(array('num'=>$addNum));
        $this->ajaxReturn(array(
            'num'=>$addNum,
            'cityId' => $cityId,
            'cityNo' => $cityNo,
            'cityName' => $cityName,
            'url'=> $pageQuantity
        ));
    }
    /*public function updateHotel(){//酒店信息更新
        //$cityId = 17;
        //$cityNo = '4401';
        //$cityName = '广州市';
        $cityId = $_POST['cityId'];
        $cityNo = $_POST['cityNo'];
        $cityName = $_POST['cityName'];
        $str = file_get_contents('http://www.wyn88.com/resv/city_'.$cityNo.'.html?cityName='.$cityName);
        $ptn = '/pageNo=(\d+)">\d+<\/a><span class=\'left\'>/';
        preg_match_all($ptn, $str, $matches);
        $pageQuantity = intval($matches[1][0]);//匹配pageNo页数
        $pageQuantity = $pageQuantity ? $pageQuantity : 1;
        //var_dump($pageQuantity);
        $h = M('hotel');
        $addNum=0;//更新计数器
        //页码循环
        for($j=1; $j<=$pageQuantity; $j++){
            $strPage = file_get_contents('http://www.wyn88.com/resv/city_'.$cityNo.'.html?cityName='.$cityName.'&pageNo='.$j);
            $ptnhotel = '/<a href = "[^\"]*_(\d+)\.html" target = "_blank">([^\<]*)<\/a>/';
            preg_match_all($ptnhotel, $strPage,$mhotel);
            //页内遍历
            for($i=0;$i<count($mhotel[0]);$i++){
                //array_push($hotelList,array(
                //'cityid' => $cityId,
                //'hotelName' => $mhotel[2][$i],
                //'hotelPmsCode' => $mhotel[1][$i]));
                $data = array(
                    'hotelName' => $mhotel[2][$i],
                    'cityid' => $cityId,
                    'hotelPmsCode' => $mhotel[1][$i]
                );
                $ifHave = $h->where($data)->find();
                if($ifHave){
                    continue;
                }
                else{
                    //$data['cityid'] = $cityId;
                    //$data['hotelPmsCode'] = $mhotel[1][$i];
                    $addNum+=1;
                    $h->add($data);
                }
            }
        }
        //$this->ajaxReturn(array('num'=>$addNum));
        $this->ajaxReturn(array(
            'num'=>$addNum,
            'cityId' => $cityId,
            'cityNo' => $cityNo,
            'cityName' => $cityName,
            'url'=> $pageQuantity
        ));
        //dump($hotelList);




    }*/

}
