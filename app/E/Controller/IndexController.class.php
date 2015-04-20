<?php
namespace E\Controller;
use Think\Controller;
use Think\Page;
class IndexController extends Controller{
	
	//列表显示
	public function index(){
		/*$e = M('employees');
		$list = $e->limit(15)->select();//显示前15条记录
		$this->assign('data', $list);
		$this->display();*/
		//分页显示
		$e = M('employees');
		$count = $e->count();
		$page = new Page($count,15);
		$page_str = $page->show();
		$list = $e->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('data', $list);
		$this->assign('page', $page_str);
		$this->display();
	}
	
	
	//搜索功能
	public function search(){
		$e = M('employees');
		/*$key_name=$_POST['first_name'];
		$list = $e->where('first_name like "%'.$key_name.'%"')->limit(15)->select();
		*/
		if($_POST['first_name'] != null){
			$arr['first_name'] = $_POST['first_name'];
			$this->assign('f_name', $_POST['first_name']);
		}
		if($_POST['last_name'] != null ){
			$arr['last_name'] = $_POST['last_name'];
			$this->assign('l_name', $_POST['last_name']);
		}
		if($_POST['gender'] != null){
			$arr['gender'] = $_POST['gender'];
			$this->assign('sex', $_POST['gender']);
		}
		$list = $e->where($arr)->limit(15)->select();
		$this->assign('data', $list);
		$this->display('index');
	}
	
	public function relate(){
		
		
	}
}
