<?php
require_once 'db.class.php';
class host extends db {
	public function __construct(){
		parent::__construct();
	}

	public function getHost($id=''){
		$sql="select * from hosts ";
		if(!empty($id)){
			$sql.="where id=$id ";
		}
		$sql.=" order by id  desc ";
		return $this->select($sql);
	}
	
	public function getHostList($id=''){
		return $this->getHost($id);
	}
	
	public function getHostByGid($gid){
		$sql="select * from hostgroup hg inner join hosts h on hg.hid=h.id where hg.gid=$gid";
		return $this->select($sql);
	}
    public function getGroupNotHost($gid){
        $sql="select * from hosts where id  not in (select id from hostgroup hg right join hosts h on h.id=hg.hid where hg.gid=$gid);";
        return $this->select($sql);
    }
	
	public function addHost($ip,$hostname='',$os='',$cpu='',$ram='',$hd=''){
		if($this->checkHostExist($ip)){
			echo '主机已存在';
			return false;
		}
		$sql="insert into hosts(ip,hostname,os,cpu,ram,hd) values('".$ip."','".$hostname."','".$os."','".$cpu."','".$ram."','".$hd."')";
		if($this->insert($sql)){
			return true;
		}else{
			return false;
		}
	}
	public function checkHostExist($ip){
		$sql="select count(0) as count from hosts where ip='".$ip."'";
		$result=$this->select($sql);
		if($result[0]['count']){
			return true;
		}else{
			return false;
		}
		
	}
	public function delHost($id){
		$sql="delete from hosts where id = $id";
		$sql2="delete from hostgroup wher hid=$id";
		if($this->delete($sql) && $this->delete($sql2)){
			return true;
		}else{
			return false;
		}
	}
	public function updateHost($id,$data){
		if(is_array($data)){
			$up='';
                        foreach($data as $k=>$v){
                                $up.=$k."='".$v."',";
                        }
                        $up=rtrim($up,',');
                        $sql="update hosts set $up where id=$id";
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
