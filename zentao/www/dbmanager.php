<?php
set_time_limit(0);
$HOST="172.19.12.149";
$USER='oracle';
$PUBKEY="../PReadme.PU";
$PRIKEY="../PReadme.PR";
$DBHOST='192.168.0.252';
$DBUSER='dblogs';
$DBPASS='dbpass';
$DBNAME='dbmanager';
$logs=new logs($DBHOST,$DBUSER,$DBPASS,$DBNAME);
$ssh=new openssh($HOST,$USER,$PUBKEY,$PRIKEY);
$files=$ssh->getfiles();

class logs {
	public function __construct($DBHOST,$DBUSER,$DBPASS,$DBNAME){
		return $this->connect=mysqli_connect($DBHOST,$DBUSER,$DBPASS,$DBNAME);
	}

	private function execute($sql=''){
		return mysqli_query($this->connect,$sql);
	}

	public function getbklogs($start=0,$size=10){
		$sql="select * from logs where action ='backup' order by id desc limit $start,$size";
		$result=$this->execute($sql);
		$i=0;
		$rs=array();
		while($row=mysqli_fetch_assoc($result)){
			$rs[$i]['ip']=$row['ip'];
			$rs[$i]['time']=$row['time'];
			$rs[$i]['dbfile']=$row['dbfile'];
			$rs[$i]['note']=$row['note'];
			$i++;
		}
		return $rs;
	}

	public function getlogs($start=0,$size=10){
		$sql="select * from logs order by id desc limit $start,$size";
		$result=mysqli_query($sql);
                $i=0;
		$data=array();
                while($row=mysqli_fetch_array($result)){
                        $data[i]['ip']=$row['ip'];
                        $data[i]['time']=$row['time'];
                        $data[i]['action']=$row['action'];
                        $data[i]['dbfile']=$row['dbfile'];
                        $data[i]['tablename']=$row['tablename'];
                        $data[i]['note']=$row['note'];
                        $i++;
		}
		return $data;
	}

	public function insertlogs($time,$action,$dbfile,$tablename,$note){
                $sql="insert into logs(ip,time,action,dbfile,tablename,note) values('".$_SERVER['REMOTE_ADDR']."','".$time."','".$action."','".$dbfile."','".$tablename."','".$note."')";
                $this->execute($sql);
        }

}

class openssh {
	public function __construct($HOST,$USER,$PUBKEY,$PRIKEY,$PORT=22){
		$this->HOST=$HOST;
		$this->USER=$USER;
		$this->PUBKEY=$PUBKEY;
		$this->PRIKEY=$PRIKEY;
		$this->PORT=$PORT;
		$this->connection=ssh2_connect($this->HOST,$this->PORT) or die("SSH Connect Error");
		ssh2_auth_pubkey_file( $this->connection, $this->USER, $this->PUBKEY, $this->PRIKEY ) or die("Authorized Error");
		return $this->connection;
	} 
	
	private function execute($cmd){
		$stream = ssh2_exec($this->connection, $cmd);
		stream_set_blocking($stream,true);
		return stream_get_contents($stream);
	}

	public function gettime(){
		$tcmd="/usr/bin/date +%Y%m%d_%H%M";
		return $this->execute($tcmd);
	}

	public function backup($time){
		$tcmd="/bin/bash /server/scripts/ora_bakcup.sh $time";
		echo nl2br($this->execute($tcmd));
	}

	public function restore($bakfile,$table=''){
		if(empty($table)){
			$tcmd="/bin/bash /server/scripts/ora_restore.sh $bakfile";
		}else{
			$tcmd="/bin/bash /server/scripts/ora_restore.sh $bakfile $table";
		}
		if($this->checkfile($bakfile)){
			echo nl2br($this->execute($tcmd));
		}else{
			echo 'File not Found!!!';
		}
	}

	public function getfiles(){
		$tcmd="ls /data/backup/dbackup/|grep dmp";
		return array_filter(explode("\n",$this->execute($tcmd)));
	}

	private function checkfile($file){
		if(in_array($file,$this->getfiles())){
			return true;
		}else{
			return false;
		}
	}
}
?>
<html><head><title>DB Manager</title></head>
<body>
<form action='' method='POST'>
<form method="post" name=myform>
选择操作：
<INPUT TYPE="radio" NAME="act" id="act_backup"  value="backup">
<label for=act_backup>备份</label>　
<INPUT TYPE="radio" NAME="act" id="act_restore" value="restore">
<label for=act_restore>恢复</label>
<br><br>恢复文件：<select name='dbfile'>
<?php
if($files){
	foreach($files as $values){
		echo "<option value ='".$values."'>".$values."</option>";
	}
}
?>
</select>
<br><br>恢复表名：<INPUT TYPE="text" NAME="tablename" value="">(如全库恢复，表名请留空！)
<br><br>备份备注：<INPUT TYPE="textarea" NAME="note" value=""><br><br>
<input type="submit" value="确定">
<input type="reset" value="取消">
<br><font color='red'>数据库备份及恢复时间较长，在执行期间请勿关闭窗口，耐心等待!</font>
<br><hr>
<?php
if($data=$logs->getbklogs()){
	foreach($data as $values){
		echo '备份文件: '.$values['dbfile'].' 时间: '.$values['time'].' 备注: '.$values['note'].'<br>';
	}
}
?>

</form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if ( $_POST['act'] == 'backup' ){
		$time=$ssh->gettime();
		$ssh->backup($time);
		$dbfile='jiesuan_bak_'.$time.'.dmp';
		$logs->insertlogs($time,$action='backup',$dbfile,$table='',$_POST['note']);
	}elseif ( $_POST['act'] == 'restore' ){
		$time=$ssh->gettime();
		if($_POST['dbfile'] and $_POST['tablename']){
                	$ssh->restore($_POST['dbfile'],$_POST['tablename']);
                	$logs->insertlogs($time,$action='restore',$_POST['dbfile'],$_POST['tablename'],$_POST['note']);
		}elseif($_POST['dbfile'] && empty($_POST['tablename'])){
			$ssh->restore($_POST['dbfile']);
			$logs->insertlogs($time,$action='restore',$_POST['dbfile'],$tablename='',$_POST['note']);
		}
	}else{
		echo "<font color='red'>请选择数据库的操作是备份还是恢复!</font>";
	}
}
?>
