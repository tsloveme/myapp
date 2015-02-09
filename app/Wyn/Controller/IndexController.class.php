<?php
namespace Wyn\Controller;
use Think\Controller;
use Think\Page;
class IndexController extends Controller{
	public function index(){
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
		$info = array("num"=>count($arr),'errlist'=>$ErrorArr,'addNum'=>$addNum,'updateNum'=>$updateNum);
		$this->ajaxReturn($info);
		//dump($arr);
		//$this->display();
	}
	public function updateCity(){
		header("Content-type: text/html; charset=utf-8"); //声明页面UTF-8;防止乱码
		$c = M('city');
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
			array_push($arr,array(
			'provinceName'=>$mProvinceName,
			'cityName'=>$mcityName,
			'cityNo'=>$mcityNo));

			
		}
	}

}
