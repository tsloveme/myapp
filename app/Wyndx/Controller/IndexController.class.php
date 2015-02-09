<?php
namespace Wyndx\Controller;
use Think\Controller;
use Think\Page;
class IndexController extends Controller{
	public function index(){
		$this->display();
	}
	
	//考试模拟
	public function testshow(){
		$this->display();
	}
	public function storeanswer(){
		$fileDir=$_POST["file"];
		echo($fileDir);
		$str = file_get_contents("../../View/Index_index.html");
		echo($str);
		echo "aaa";
		//$this->show($fileDir);
		//$this->display(index);
		
	}
	public function uploadFile(){
		//文件上传.
		$config = array(
		'maxSize' => 1024000,
		'rootPath' => './Public/',
		'savePath' => 'tem/',
		'saveName' => array('uniqid',''),
		'exts' => array('html', 'html'),
		'autoSub' => true,
		'subName' => array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);
		$info = $upload->upload();
		if(!$info) {// 上传错误提示错误信息
		$this->error($upload->getError());
		}
		else{// 上传成功 获取上传文件信息
		//dump($info);
		// foreach($info as $file){
			//echo $file['savepath'].$file['savename'];
		//}
			echo $info["file"]['savepath'].$info["file"]['savename'];
		$this->show("aaa");
		}
	}
		
}
