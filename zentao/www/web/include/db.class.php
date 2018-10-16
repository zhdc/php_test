<?php
class db {
	private $host='192.168.0.252';
	private $user='webops';
	private $passwd='webops';
	private $db='webops';
	private $port=3306;
	public function __construct(){
		try {
			return $this->conn=mysqli_connect($this->host,$this->user,$this->passwd,$this->db,$this->port) or die('数据库连接失败');
		}catch(Exception $e){
			echo '数据库连接错误信息:'.$e;
		}
	}

	public function select($sql){
		$result=mysqli_query($this->conn,$sql);
		$data=array();
		$i=0;
		while($row=mysqli_fetch_assoc($result)){
			$data[$i]=$row;
			$i++;
		}
		@mysqli_free_result($result);
		return $data;
		
	}

	public function delete($sql){
		return mysqli_query($this->conn,$sql);
	}

	public function update($sql){
		return mysqli_query($this->conn,$sql);
	}
	public function insert($sql){
		$result=mysqli_query($this->conn,$sql);
		return mysqli_insert_id($this->conn); 
	}
}
?>
