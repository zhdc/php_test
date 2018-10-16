<?php
require_once 'db.class.php';
class group extends db {
	public function __construct(){
		 parent::__construct();
	}
	
	public function checkGroupExist($name){
		$sql="select count(0) as count from groups where name='".$name."'";
		$result=$this->select($sql);
		if($result[0]['count']==0){
			return false;
		}else{
			return true;
		}
		
	}
	public function getGroupList(){
		$sql="select g.id,g.name,g.note,count(*) as count from hostgroup hg  right join groups g on g.id=hg.gid group by g.id,g.name,g.note order by g.id desc";
		return $this->select($sql);
		
	}

	public function getGroupHost($gid){
		$sql="select * from hostgroup where gid =$gid";
		return $this->select($sql);

	}

	public function addGroup($name,$note){
		if($this->checkGroupExist($name)){
			echo '组已存在';
			return false;
		}else{
			$sql="insert into groups(name,note) values('".$name."','".$note."')";
			if($this->insert($sql)){
				return true;
			}
		}

	}
	public function delGroup($gid){
		$sql="delete from groups where id=$gid";
		$sql2="delete from hostgroup where gid=$gid";
		if($this->delete($sql) && $this->delete($sql2)){
			return true;
		}else{
			return false;
		}

	}
        public function updateGroup($id,$data){
                if(is_array($data)){
                        $up='';
                        foreach($data as $k=>$v){
                                $up.=$k."='".$v."',";
                        }
                        $up=rtrim($up,',');
                        $sql="update groups set $up where id=$id";
                        if($this->update($sql)){
                                return true;
                        }else{
                                return false;
                        }
                }else{
                        return false;
                }
        }

	public function addHostToGroup($hid,$gid){
		$sql="select count(0) as count from hostgroup where hid=$hid and gid=$gid";
		$result=$this->select($sql);
		if($result[0]['count']){
			return false;
		}
		$sql="insert into hostgroup(hid,gid) values($hid,$gid)";
		if($this->insert($sql)){
			return true;
		}else{
			return false;
		}
	}
	public function delHostToGroup($hid,$gid){
		$sql="delete from hostgroup where hid=$hid and gid=$gid";
		return $this->delete($sql);
	}
	public function getHostGroupByHid($hid){
		$sql="select g.name from hostgroup hg inner join groups g on hg.gid=g.id  where hg.hid=$hid";
                return $this->select($sql);
	}
	public function getHostGroupByGid($gid){
		$sql="select h.* from hostgroup hg inner join hosts h on hg.hid=h.id  where gid=$gid";
		return $this->select($sql);
	}
	public function getHostGroup(){
		$sql="select h.*,g.name from hosts h inner join hostgroup hg on hg.hid=h.id inner join groups g on g.id=hg.gid";
		return $this->select($sql);
	}
	public function getGroup($gid){
		$sql="select * from groups where id=$gid";
		return $this->select($sql);
	}
	
}
?>
