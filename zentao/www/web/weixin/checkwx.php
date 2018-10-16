<?php
require_once 'config.php';
$result=$M->getlinks();
$ch = curl_init();
$timeout=5;
if(is_array($result)){
	foreach($result as $k=>$v){
		$aa=explode('|',$v['domain']);
		foreach($aa as $g){
		$url = "http://www.58icp.com/index.php?mod=getweixin&domain=".$g;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		www:
		$contents = curl_exec($ch);
		$result=json_decode($contents);
		$res=object_array($result);
		if($res['status']==2){
			$M->updateDomainStatus($g);
			echo $g.'域名被封';
		}elseif($res['status']==3){
			echo '超过API限制';
			sleep(2);
			goto www;
		}
		sleep(3);
		}
	}
}


function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
             }  
     }  
     return $array;  
}



curl_close($ch);
?>