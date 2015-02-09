<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    //首页列表显示
	public function index(){
		$article = M("article");
		$list = $article->limit(10)->select();
		$this->assign('list',$list);
        $this->display();
    }
	//文章添加
	public function addArticle(){
		$a = M('article');
		$data['title'] = $_POST['title'];
		$data['description']=$_POST['description'];
		$data['content'] = $_POST['content'];
		$arr = $a->add($data);
		if($arr > 0){
			$this->success('添加成功!','index');
			}
		else{
			$this->error('添加失败！');
			}
	}
	//删除文章
	public function delete($id){
		$a = M('article');
		$arr = $a->delete($id);	
		if($arr > 0){
			$this->success('删除成功！',U('Index/add'));	
		}
		else{
			$this->error('删除失败！',U('Index/add'));	
		}
	}
	//显示文章
	public function read($id){
		$a = M('article');
		//$detial = $a->select($id); select 是返回二维数组;
		$detial = $a->find($id);// find是返回一维数组;
		$this->assign('detial',$detial);
		if(!($id)|| !($detial)){
			$this->error('文章不存在或者参数错误！');	
		}
		else{
			$this->display();
			
		}
	}
	
	//更新
		//更新载入
	public function edit($id){
		$a = M('article');
		$this->data = $a->find($id);
		if(!$id || !($this->data)){
			$this->error('无效参数/文章已不存在！', U('Index'),3);	
		}
		else{
			$this->display();	
		}
	}
		//处理更新操作
	public function update(){
		$a = M('article');
		if($a->create()){
			$result = $a->save();
			if($result){
				$this->success('更新成功',U('Index'));	
			}
			else{
				$this->error('更新失败', U('Index'));	
			}
		}
		else{
			$this->error($a->getError());	
		}
	}
	
}