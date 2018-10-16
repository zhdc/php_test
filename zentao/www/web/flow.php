<?php
require_once 'include/cmd.class.php';
require_once 'include/group.class.php';
require_once 'include/flow.class.php';
require_once 'include/arg.class.php';
require_once 'include/host.class.php';
require_once 'include/ssh.class.php';
$group=new group();
$cmd = new cmd();
$flow=new flow();
$arg=new arg();
$host=new host();
?>
<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Web运维系统</title>
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="assets/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->



  <!-- content start -->
<?php
if($_SERVER['REQUEST_METHOD']=="POST" AND $_POST['cid']){
?>
     <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form" action='' method='POST'>
		  <input type='hidden' name='action' value='addproc'>
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-type">执行排序</th><th class="table-title">命令</th><th class="table-type">服务器</th><th class="table-title">命令内容</th>
              </tr>
          </thead>
          <tbody>
 <?php
$data=$cmd->getCmds($_POST['cid']);
$groupdata=$group->getGroupList();
if(is_array($data)){
	$i=1;
	foreach($data as $k=>$v){
?>
            <tr>
			  <input type='hidden' name='cid[<?php echo $v['id']; ?>]' value='<?php echo $v['id']; ?>'>
			  <td><input type=number name='order[<?php echo $v['id']?>]' value='<?php echo $i; ?>'></td>
              <td><?php echo $v['name']; ?></td>
			  <td>
			  <select name='gid[<?php echo $v['id']?>]'>
			  <?php
				if(is_array($groupdata)){
					foreach($groupdata as $g){
						echo "<option value=".$g['id'].">".$g[name]."</option>";
					}
				}
			  ?>
			  </select>
			  </td>
			  <td><?php echo $v['cmd']; ?></td>
            </tr>
<?php
	$i++;
	}
}
?>

          </tbody>
        </table>
            <div class="am-u-sm-4 am-u-end">
			<input type='text' name='name' value='' placeholder="流程名称">
			</div>
			<br><br>
 		<div class="am-margin">
			<button type="submit" class="am-btn am-btn-primary am-btn-xs">保存</button>
			<button type="reset" class="am-btn am-btn-primary am-btn-xs">取消</button>
		</div>
		</form>
<?php
}elseif($_GET['fid'] && $_GET['action']=='delete'){
	if($flow->delFlow($_GET['fid'])){
		echo "<script>alert('删除成功');</script><script>window.location.go(-1);</script>";
	}
}elseif($_GET['fid'] && $_GET['action']=='edit'){
	echo "编辑";
}elseif($_GET['fid'] && $_GET['action']=='run'){
	$data=$flow->getFlow($_GET['fid']);
	echo '<form action="" method="POST">';
	foreach($data as $k=>$v){
		$cid_arr=array();
		$i=0;
		foreach($v[0] as $kk=>$vv ){
			$cid_arr[$i]=$vv['cid'];
			$i++;
		}
		
		$result=$arg->getManyArgs($cid_arr);
		foreach($result as $v){
			echo "<input type='hidden' name='run' value='true'>";
			echo "<input type='text' size=50 name=".$v['arg']." value='' placeholder=".$v['arg']."><br>";
		}
	}
	echo '<input type="submit"	value="提交">';
	echo '<input type="reset" value="重置"></form>';
	if($_SERVER['REQUEST_METHOD']=='POST' AND $_POST['run']=='true'){
		unset($_POST['run']);
		foreach($data as $k=>$v){
			foreach($v[0] as $kk=>$vv ){
				$result=$host->getHostByGid($vv['gid']);
				$cmds=$cmd->getCmdByID($vv['cid']);
				if(is_array($result)){
					foreach($result as $kkk=>$vvv){
						echo $vvv['ip'].'执行情况:<br>';
						if(strstr(strtolower($vvv['os']),'wind')){
							${$vvv['ip']}=new ssh($vvv['ip'],'windows');
						}else{
							${$vvv['ip']}=new ssh($vvv['ip']);
						}
						echo nl2br(${$vvv['ip']}->cmd($cmds[0]['cmd'],$_POST));
			
					}
				}
			}
		}
		/*
		
		*/
	}
}else{

?>
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='run.php'>流程</a></strong> / <small><a href='cmd.php'>创建流程</a></small></div>
    </div>
     <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form" action='' method='POST'>
		  <input type='hidden' name='action' value='addproc'>
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-type">执行排序</th><th class="table-type">命令名称</th><th class="table-type">命令内容</th><th class="table-title">服务器</th>
              </tr>
          </thead>
          <tbody> 
<?php
$data=$flow->getFlow();
if(is_array($data)){
	$i=0;
	foreach($data as $k=>$v){
?>
            <tr>
			<td>流程名称:</td><td > <a href=?fid=<?php echo $v['id']; ?>><?php echo $v['name'] ?></a></td>
			<td colspan="4">
			<a href='?fid=<?php echo $v['id'] ?>&action=run'><button type="button" class="am-btn am-btn-default">执行</button></a>
			<a href='?fid=<?php echo $v['id'] ?>&action=edit'><button type="button" class="am-btn am-btn-default">修改</button></a>
			<a href='?fid=<?php echo $v['id'] ?>&action=delete'><button type="button" class="am-btn am-btn-default">删除</button></a></td>
			
			<?php
				foreach($v[$i] as $kk=>$vv){
			?>
			  </tr>
			  <td><?php echo $vv['oid']; ?></td>
			  <td><?php echo $vv['cname']; ?></td>
			  <td><?php echo $vv['cmd']; ?></td>
			  <td><?php echo $vv['gname']; ?></td>
			  </tr>
			 <?php
				}
			 ?>
            
			
<?php
	$i++;
	}
}
?>
<?php
}
?>
<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/polyfill/rem.min.js"></script>
<script src="assets/js/polyfill/respond.min.js"></script>
<script src="assets/js/amazeui.legacy.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>

<!--<![endif]-->
<script src="assets/js/app.js"></script>
</body>
</html>
<?php
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['action']=="addproc"){
	unset($_POST['action']);
	$data=$_POST;
	if($flow->addFlow($data)){
		echo "<script>alert('保存成功')</script>";
	}
}

?>