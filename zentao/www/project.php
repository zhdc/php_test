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
$ssh=new openssh($HOST,$USER,$PUBKEY,$PRIKEY);


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

	public function create($project){
		$tcmd="sudo /bin/bash /server/scripts/init.sh $project";
		echo nl2br($this->execute($tcmd));
	}

}
?>
<html><head><title>项目管理</title></head>
<body>
<form action='' method='POST'>
请输入项目名称：<INPUT TYPE="text" NAME="project" value=""><br>
 *请输入源码名称或者站点名称<br>
<input type="submit" value="确定">
<input type="reset" value="取消">
</form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if($_POST['project']){
                	$result=$ssh->create(trim($_POST['project']));
		}
}
?>
