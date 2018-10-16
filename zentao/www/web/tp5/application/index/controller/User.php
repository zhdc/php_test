<?php
namespace app\index\controller;
use app\index\model\User;
use think\Controller;

class Index extends Controller {
	public function index(){
		$User = new User;
		$result= $User->save($data);
		if($result){
			$this->success('新增成功','User/list');
		}else{
			$this->error('新增失败');
		}
	}
	
	public function list(){
		$User= new User;
		return $User->get();
	}
	
}
?>