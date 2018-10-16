<?php
function curl($url,$curlPost='',$timeout=5){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if(!empty($curlPost)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8','Content-Length:' . strlen($curlPost)));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
}



function alert($domain=''){
	if(empty($domain)){
		exit;
	}
	$corpid='ww753878b2ba10278e';
	$corpsecret='1i07W0E8ionjiY339wfBK-8agRJWkhTW1jg2kvY5O-Q';
	$wxurl='https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$corpid.'&corpsecret='.$corpsecret;
	$result=curl($wxurl);
	$data=json_decode($result,true);
	$token=$data['access_token'];
	
	$purl='https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token='.$token;
	$content="请注意:\n $domain 无法使用! \n 时间:".date("Y-m-d H:i:s");
	$post_data=array(
		"touser" => "@all",
		"msgtype" => "text",
		"agentid" => 1000002,
		"text" => array(
			"content" => $content
		)
	);
	echo curl($purl,json_encode($post_data));	
}
?>