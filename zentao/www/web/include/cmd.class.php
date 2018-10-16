<?php
require_once 'db.class.php';
class cmd extends db {
	public function __construct(){
		parent::__construct();
	}

	public function getCmdByID($id){
		$sql="select * from cmds where id=$id";
		return $this->select($sql);
	}
	public function getCmds($ids){
		$sql='select * from cmds where id in ('.implode(',',$ids).')';
		return $this->select($sql);
	}

	public function getCmdList(){
		$sql="select * from cmds order by id desc";
		return $this->select($sql);
	}

	public function addCmd($cmd,$name='',$menu='',$note=''){
		$sql="insert into cmds(cmd,name,menu,note) values('".$cmd."','".$name."','".$menu."','".$note."')";
		if($id=$this->insert($sql)){
			return $id;
		}else{
			return false;
		}
	}

	public function delCmd($id){
		$sql="delete from cmds where id=$id";
		if($this->delete($sql)){
			return true;
		}else{
			return false;
		}
	}
	public function updateCmd($id,$data){
		if(is_array($data)){
			foreach($data as $k=>$v){
				$up.=$k."='".$v."',";
			}
			$up=rtrim($up,',');
			$sql="update cmds set $up where id=$id";
			if($this->update($sql)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
?>
