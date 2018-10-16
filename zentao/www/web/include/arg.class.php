<?php
require_once 'db.class.php';
class arg extends db {
	public function __construct(){
		parent::__construct();
	}

	public function addArgs($cid,$arg,$name,$note=''){
		$sql="insert into args(cid,arg,name,note) values($cid,'".$arg."','".$name."','".$note."')";
		if($this->insert($sql)){
			return true;
		}else{
			return false;
		}
	}

	public function delArg($id){
		$sql="delete from args where id=$id";
		if($this->delete($sql)){
                        return true;
                }else{
                        return false;
                }
	}

	public function getArgs($cid){
		$sql="select * from args where cid=$cid";
		return $this->select($sql);
	}
	public function updateArgs($id,$data){
                if(is_array($data)){
                        foreach($data as $k=>$v){
                                $up.=$k."='".$v."',";
                        }
                        $up=rtrim($up,',');
                        $sql="update args set $up where id=$id";
                        if($this->update($sql)){
                                return true;
                        }else{
                                return false;
                        }
                }else{
                        return false;
                }
	}
	
	public function getManyArgs($data){
		$cid=implode(',',$data);
		$sql="select DISTINCT arg from args where cid in ( $cid )";
		return $this->select($sql);
	}
}

?>
