<?php
include 'config.php';

if(stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){
	header('Location:'.$notwxlink);
	exit();
}

$url=parse_url($_SERVER['REQUEST_URI']);
//跳转广告链接
if(substr($url['path'],1,2)== 'ad'){
	$adlink=str_replace("?","!*",$adlink);
	$adlink=str_replace("&","!@",$adlink);
	header('Location:'.$adlink);
	exit();
}
//跳转到随机域名的导流链接
$rlink='http://'.$S->getredirectlink().'/wxmp/'.mt_rand(10,10000).'.html';
header('Location:'.$rlink);
?>
