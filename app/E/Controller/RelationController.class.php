<?php
namespace E\Controller;
use Think\Controller;
class RelationController extends Controller{
	public function index(){
		$de = D('DeptEmp');
		$data = $de->relation(true)->limit(5)->select();
		
		dump($data);
		exit;
	}
	public function test(){
		
		
	}
}
