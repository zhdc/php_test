<?php
$data['phone']=$_POST['identity'];
$data['password']=$_POST['password'];
header('Content-type: application/json');
echo json_encode($data);
?>
