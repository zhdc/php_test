 <?php
include 'config.php';
//公众号id
$inid = $result['id'];
if(isset($_GET['error'])){
	//获取到错误参数
	$error = date("Y-m-d H:i:s").' '.$_GET['error'].'\n\r';
	error_log($error, 3, 'logs/wxerror.log');
	//判断json字符串中是否存在48004错误，说明域名不可用
	if(strpos($error,'48004') !== false){
		$safe_link = $_SERVER['HTTP_HOST'];
		//查询出该域名的id
		$id = $M->getsafelinkid($safe_link);
		//将该域名状态改为2
		$S->update(['status'=>2,'id'=>$id]); 
	}elseif(strpos($error,'50002') !== false){
		//否则判断json字符串中是否存在50002错误，说明公众号不可用
		//将该域名状态改为2
		$M->update(['status'=>2,'id'=>$inid]);  
	}
}else{
	$M->pagetongji($result['name']);
}



?>