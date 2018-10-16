<?php
require_once './include/cmd.class.php';
require_once './include/arg.class.php';
require_once './include/group.class.php';
require_once './include/host.class.php';
require_once './include/ssh.class.php';
$group=new group();
$host=new host();
$cmd=new cmd();
$arg=new arg();
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
 <div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='run.php'>命令执行</a></strong> / <small>执行</small></div>
    </div>
	<?php 
		if($_GET['step']==1 || !isset($_GET['step'])){
	?>
        <form class="am-form" method="GET" action=''>
		<div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              服务器组
            </div>
            <div class="am-u-sm-4 am-u-end">
			<select name='gid'>
			<?php
				
				$data=$group->getGroupList();
				if(is_array($data)){
					foreach($data as $k=>$v){
						echo "<option value=".$v['id'].">".$v[name]."</option>";
					}
				}
			?>
			</select>
			<input type='hidden' name='step' value=2></input>
            </div>
          </div>
  <div class="am-margin">
    <button type="submit" class="am-btn am-btn-primary am-btn-xs">下一步</button>
  </div>
		</form>
		<?php
		}elseif($_GET['gid'] && $_GET['step']==2){
		?>
		<form class="am-form" method="GET" action=''>
		<div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              命令
            </div>
            <div class="am-u-sm-4 am-u-end">
			<select name='cid'>
			<?php
				$data=$cmd->getCmdList();
				if(is_array($data)){
					foreach($data as $k=>$v){
						echo "<option value=".$v['id'].">".$v['name']."</option>";
					}
				}
			?>
			</select>
			<input type='hidden' name='step' value=3></input>
			<input type='hidden' name='gid' value=<?php echo $_GET['gid']; ?>></input>
            </div>
          </div>
		<div class="am-margin">
			<button type="submit" class="am-btn am-btn-primary am-btn-xs">上一步</button>
			<button type="submit" class="am-btn am-btn-primary am-btn-xs">下一步</button>
		</div>
		</form>
		<?php
		}elseif($_GET['gid'] && $_GET['cid'] && $_GET['step']==3){
		?>
		<form class="am-form" method="POST" action=''>
		<div class="am-g am-margin-top-sm">   
			<?php

				$data=$arg->getArgs($_GET['cid']);
				$data2=$cmd->getCmdByID($_GET['cid']);
				if(is_array($data)){
					echo '<div class="am-u-sm-2 am-text-right">命令名称</div>';
					echo "<div class='am-u-sm-4 am-u-end'><input type='text'  class='am-input-sm' readonly='readonly' value='".$data2[0]['name']."' value='name'></div>";
					echo '<div class="am-u-sm-2 am-text-right">命令内容</div>';
					echo "<div class='am-u-sm-4 am-u-end'><input type='text'  class='am-input-sm' readonly='readonly' value='".$data2[0]['cmd']."' value='cmd'></div>";
					foreach($data as $k=>$v){
						echo '<div class="am-u-sm-2 am-text-right">'.$v['name'].'</div>';
						echo "<div class='am-u-sm-4 am-u-end'><input type='text'  class='am-input-sm' name='".$v['arg']."' value=''></div>";
					}
				}
				/*
				foreach($_GET as $k=>$v){
					echo "<input type='hidden' name='".$k."' value='".$v."'>";
				}
				*/
			?>
			
			
          </div>
		<div class="am-margin">
			<button type="submit" class="am-btn am-btn-primary am-btn-xs">上一步</button>
			<button type="submit" class="am-btn am-btn-primary am-btn-xs">下一步</button>
		</div>
		</form>			
		<?php	
		}
		?>
  <!-- content end -->
<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
	$result=$host->getHostByGid($_GET['gid']);
	$cmds=$cmd->getCmdByID($_GET['cid']);
	if(is_array($result)){
		foreach($result as $k=>$v){
			echo $v['ip'].'执行情况:<br>';
			if(strstr(strtolower($v['os']),'wind')){
				${$v['ip']}=new ssh($v['ip'],'windows');
			}else{
				${$v['ip']}=new ssh($v['ip']);
			}
			echo nl2br(${$v['ip']}->cmd($cmds[0]['cmd'],$_POST));
			
		}
	}
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
