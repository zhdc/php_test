<?php
/**
 * Created by PhpStorm.
 * User: HSAEE
 * Date: 2017-11-25
 * Time: 1:18
 */
$url=$_REQUEST["url"];
$url=str_replace("!*","?",$url);
$url=str_replace("!@","&",$url);
header("Location:$url");
?>