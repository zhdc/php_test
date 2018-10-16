<?php
set_time_limit(0);

class ssh {
	private $pubkey='PReadme.PU';
	private $prikey='PReadme.PR';
	public function __construct($host='',$os='linux',$username='root',$authtype='password',$password='www.qilin70.cn',$port=22){
		$this->host=$host;
		$this->username=$username;
		$this->password=$password;
		$this->port=$port;
		$this->authtype=$authtype;
		$this->os=$os;
		if(strstr(strtolower($this->os),'windows')){
			$this->os='windows';
			$this->username='Administrator';
		}
		$this->connection=ssh2_connect($this->host,$this->port ) or die("SSH Connect Error");
		if($this->authtype=='password'){
			ssh2_auth_password( $this->connection, $this->username, $this->password) or die("Password Authorized Error");
		}else{
			ssh2_auth_pubkey_file( $this->connection, $this->username, $this->pubkey, $this->prikey ) or die("Key Authorized Error");
		}
		return $this->connection;
	} 
	
	public function execute($cmd){
		$stream = ssh2_exec($this->connection, $cmd );
		stream_set_blocking($stream,true);
		if($this->os=='windows'){
			return mb_convert_encoding(stream_get_contents($stream),'UTF-8','GBK');
		}else{
			return stream_get_contents($stream);
		}
	}

	public function cmd($cmd,$args=''){
		if(is_array($args)){
			foreach($args as $k=>$v){
				$cmd=str_replace($k,$v,$cmd);
			}
		}
		return $this->execute(stripslashes($cmd));
	}
	
	public function downftp($remote,$local){
		$this->os=='windows' && strpos($remote,':') && $remote='/cygdrive/'.str_replace(':','',$remote);
		$resftp=ssh2_sftp($this->connection);
		return copy("ssh2.sftp://{$resftp}".$remote,$local);
	}
	
	public function upftp($local,$remote,$file_mode=0777){
		$this->os=='windows' && strpos($remote,':') && $remote='/cygdrive/'.str_replace(':','',$remote);
		$resftp=ssh2_sftp($this->connection);
		return copy($local,"ssh2.sftp://{$resftp}".$remote);
	}
	
	public function getfile($file){
		$temp_file='./tmp/'.basename($file).mt_rand(1,10000000000000);
		if($this->downftp($file,$temp_file)){
			$myfile = fopen($temp_file, "r") or die("Unable to open file!");
			$data['content']=fread($myfile,filesize($temp_file));
			fclose($myfile);
			@unlink($temp_file);
			$data['filename']=$temp_file;
			return $data;
		}
		return false;
	}
	
	public function savefile($file,$data){
		$temp_file='./tmp/'.basename($file).mt_rand(1,10000000000000);
		if(file_put_contents($temp_file,str_replace('<br />','',$data))){
			if($this->upftp($temp_file,$file)){
				@unlink($temp_file);
				return true;
			}
		}
	}	
}
?>
