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
		header("Content-type: text/html; charset=utf-8");
		//$fileDir=$_POST["file"];
		//echo($fileDir);
		$str = file_get_contents(C('Public').'/tem/20150208/exam1.html');
		//单选题匹配
		$ptnSigQue='/<div id="questionNumber\d*" class="question-content" queid="\d*">[\s\S]*?sin_que_option[\s\S]*?((<\/table>)|(([ABCD])[\s\n]*))<\/div>[\s\n]*<\/div>/';
		preg_match_all($ptnSigQue,$str,$mASigQue);
		//dump($m[4]);
		
		//判断题
		$ptnJudge = '/标准答案：[\s\n]*?<span class="judge-((false)|(true))">[&nbsp;]*?\s*<\/span>/';
		preg_match_all($ptnJudge,$str,$mAJudge);
		//dump($mAJudge);
		
		//填空题
		$ptnFillQue='/<br>标准答案：[\s\S]*?<\/div>[\s\n]*<div style="clear: both;"><\/div>/';
		preg_match_all($ptnFillQue,$str,$mTem);
		dump($mTem);
		
		
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
