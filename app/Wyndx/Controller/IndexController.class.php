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
	//考试模拟1
	public function testshow1(){
		$this->display();
	}
	public function storeanswer($pram){
		header("Content-type: text/html; charset=utf-8");
		//$fileDir=$_POST["file"];
		//echo($fileDir);
		//$str = file_get_contents(C('Public').'/tem/20150208/1222.html');
		//$fileDir = $fileDir;
		$fileDir = $pram;
		$str = file_get_contents(C('Public').'/'.$fileDir);
		$e = M('exam');
		//匹配出卷时间：
		$ptnExamTime = '/<td colspan="\d*">出卷时间：([\d\-\s\:]*)<\/td>/i';
		if(preg_match_all($ptnExamTime,$str,$mExamTime)){
			//var_dump($mExamTime[1]);
			$data['examTime'] = date("Y-m-d H:i:s",strtotime($mExamTime[1][0]));
		}
		//匹配考试时间
		$ptnDoneTime = '/<td>交卷时间：([\d\-\s\:]*)<\/td>/i';
		if(preg_match_all($ptnDoneTime,$str,$mDoneTime)){
			//var_dump($mDoneTime[1]);
			$data['examDoneTime'] = date("Y-m-d H:i:s",strtotime($mDoneTime[1][0]));
		}
		//匹配标题
		$ptnTitle = '/<div id="title">([^\<]*)<\/div>/i';
		if(preg_match_all($ptnTitle, $str, $mTitle)){
			$data['examTitle'] = $mTitle[1][0];
		}
		//匹配出卷人
		$ptnAuthor = '/<td>出卷人：([^\<]*)<\/td>/i';
		if(preg_match_all($ptnAuthor,$str,$mAuthor)){
			$data['examAuthor'] = $mAuthor[1][0];
		}
		
		//单选题匹配
		$ptnSigQue='/<div id="questionNumber\d*" class="question-content" queid="\d*">[\s\S]*?sin_que_option[\s\S]*?((<\/table>)|(([A-Z])[\s\n]*))<\/div>[\s\n]*<\/div>/i';
		if(preg_match_all($ptnSigQue,$str,$mASigQue)){
			//dump($mASigQue[4]);
			$data['examAnswerSin'] = join("$$",$mASigQue[4]);
		}
		
		//多选题匹配
		$ptnMultiQue='/<label for="unmu_que_option_\d*">[\s\S]*?标准答案：([A-Z\s]*)/i';
		if(preg_match_all($ptnMultiQue,$str,$mAMultiQue))
		{	
			$arrMultiQue = array();
			foreach($mAMultiQue[1] as $k=>$v){
				array_push($arrMultiQue,preg_replace('/\s+/','',$v));
			}
			//dump($arrMultiQue);
			$data['examAnswerMul'] = join("$$",$arrMultiQue);
		}
		
		
		//判断题
		$ptnJudge = '/标准答案：[\s\n]*?<span class="judge-((false)|(true))">[&nbsp;]*?\s*<\/span>/i';
		if(preg_match_all($ptnJudge,$str,$mAJudge)){
			//dump($mAJudge[1]);
			$data['examAnswerJud'] = join("$$",$mAJudge[1]);
		}
		
		//填空题
		$ptnFillQue='/<br>标准答案：[\s\S]*?<\/div>[\s\n]*<div style="clear: both;"><\/div>/i';
		if(preg_match_all($ptnFillQue,$str,$mTem)){
			//匹配每题答案
			//dump($mTem);
			$fillQueAll=array();
			$ptnPerFillQue = '/\d、(.*?)；\&nbsp;/';
			foreach($mTem[0] as $k=>$v){
				preg_match_all($ptnPerFillQue,$v,$m);
				foreach($m[1] as $k=>$v){
					array_push($fillQueAll,$v);
				}
			}
			//dump($fillQueAll);
			$data['examAnswerFil'] = join("$$",$fillQueAll);
		}
		//TestId
		$ptnTestId = '/<input type="hidden" id="testid" name="testid" value="([\d]+)"/i';
		if(preg_match_all($ptnTestId,$str,$mTestId)){
			$data["examTestId"] = $mTestId[1][0];
		}
		dump($data);
		if($e->add($data)){
			$this->success("处理成功！","index",5);
		}
		else{
			$this->error("处理失败！","index",3);
		}

		
	}
	public function uploadFile(){
		header("Content-type: text/html; charset=utf-8");
		//文件上传.
		$config = array(
		'maxSize' => 2048000,
		'rootPath' => './Public/',
		'savePath' => 'tem/',
		'saveName' => array('uniqid',''),
		'exts' => array('html','htm'),
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
			//echo($info["file"]['savepath'].$info["file"]['savename']);
			//echo C('Public').'/'.$info["file"]['savepath'].$info["file"]['savename'];
			$result = $this->storeanswer($info["file"]['savepath'].$info["file"]['savename']);
		}
	}
	//获取答案列表
	public function getAnswerList(){
		header("Content-type: text/html; charset=utf-8");
		$e = M('exam');
		$data = $e->field('examId,examTestId,examTitle,examAuthor,examTime')->where('examChecked=1')->select();
		$this->ajaxReturn($data);
	}
	public function getAnswerList_GET(){//jsonp方式获取
		$e = M('exam');
		$callback = $_GET['callback'];
		$data = $e->field('examId,examTestId,examTitle,examAuthor,examTime')->select();
		//$this->ajaxReturn($data);
		$result = json_encode($data);
		echo $callback.'('.$result.')';
		exit;
	}
	//获取题目答案（jsonp方式）
	public function getAnswer_GET(){
		header("Content-type: text/html; charset=utf-8");
		$examid=$_GET['examid'];
		$callback=$_GET['callback'];
		//$examid='19';
		
		$e = M('exam');
		$data = $e->find($examid);
		//dump($data);
		$arr = array();

		if($data["examanswersin"]!=null){
			$examanswersin = explode('$$',$data["examanswersin"]);
			
		}
		if($data["examanswermul"]!=null){
			$examanswermul = explode('$$',$data["examanswermul"]);
			
		}
		if($data["examanswerjud"]!=null){
			$examanswerjud = explode('$$',$data["examanswerjud"]);
			
		}
		if($data["examanswerfil"]!=null){
			$examanswerfil = explode('$$',$data["examanswerfil"]);
			
		}
		//dump($arr);
		//$this->ajaxReturn(array('examanswersin'=>$examanswersin,'examanswermul'=>$examanswermul,'examanswerjud'=>$examanswerjud,'examanswerfil'=>$examanswerfil));
		//$this->ajaxReturn($arr);
		$result = json_encode(array('examanswersin'=>$examanswersin,'examanswermul'=>$examanswermul,'examanswerjud'=>$examanswerjud,'examanswerfil'=>$examanswerfil));
		echo $callback.'('.$result.')';
	}
	//获取题目答案
	public function getAnswer(){
		header("Content-type: text/html; charset=utf-8");
		//$examid=$_POST['examid'];
		$examid=$_GET['examid'];
		//$examid='19';
		
		$e = M('exam');
		$data = $e->find($examid);
		//dump($data);
		$arr = array();

		if($data["examanswersin"]!=null){
			$examanswersin = explode('$$',$data["examanswersin"]);
			
		}
		if($data["examanswermul"]!=null){
			$examanswermul = explode('$$',$data["examanswermul"]);
			
		}
		if($data["examanswerjud"]!=null){
			$examanswerjud = explode('$$',$data["examanswerjud"]);
			
		}
		if($data["examanswerfil"]!=null){
			$examanswerfil = explode('$$',$data["examanswerfil"]);
			
		}
		//dump($arr);
		$this->ajaxReturn(array('examanswersin'=>$examanswersin,'examanswermul'=>$examanswermul,'examanswerjud'=>$examanswerjud,'examanswerfil'=>$examanswerfil));
		//$this->ajaxReturn($arr);
	}
}
















