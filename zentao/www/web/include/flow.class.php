<?php
require_once 'db.class.php';
class flow extends db {
	public function __construct(){
		parent::__construct();
	}
	
	public function addFlow($data){
		$sql="insert into flow(name) values('".$data['name']."')";
		if($fid=$this->insert($sql)){
			foreach($data['cid'] as $k=>$v){
				$cid=$data['cid'][$k];
				$gid=$data['gid'][$k];
				$oid=$data['order'][$k];
				$sql="insert into flowdetail values($fid,$cid,$gid,$oid)";
				$this->insert($sql);
			}
			return true;
		}
	}
	
	public function getFlow($id=''){
		$sql="select * from flow";
		if(!empty($id)){
			$sql.=" where id = $id";
		}
		$sql.=" order by id desc ";
		$data=$this->select($sql);
		if(count($data)==0){
			return NULL;
		}

		$i=0;
		foreach($data as $k=>$v){
			$data[$k][$i]=$this->getFlowDetail($v['id']);
			$i++;
		}
		return $data;
	}
	
	public function getFlowDetail($id){
		$sql="select fd.oid,c.name,c.id as cid,c.cmd,c.name as cname,g.name as gname,g.id as gid from flowdetail fd left join cmds c on fd.cid=c.id left join groups g on g.id=fd.gid 	WHERE fd.fid=$id ";
		$sql.="order by  fd.fid desc,fd.oid asc";
		return $this->select($sql);
	}
	
	public function delFlow($id){
		$sql="delete from flow where id =$id";
		$sql2="delete from flowdetail where fid=$id";
		if($this->delete($sql) && $this->delete($sql2)){
			return true;
		}else{
			return false;
		}
	}
	
	
	
	
	
	
}
?>