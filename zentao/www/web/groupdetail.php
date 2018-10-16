<?php
require_once './include/host.class.php';
require_once './include/group.class.php';
$host=new host();
$group=new group();
if($_GET['gid']){
        $data=$host->getHostByGid($_GET['gid']);
		$data2=$host->getGroupNotHost($_GET['gid']);
}
if($_SERVER['REQUEST_METHOD']=='POST'){
	if($_POST['action']=='addgroup'){
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $v){
				$group->addHostToGroup($v,$_GET['gid']);
			}
			echo "<script>alert('组成员已增加');window.location.go(-1);</script>";
		}
	}elseif($_POST['action']=='removegroup'){
		if(is_array($_POST['id'])){
			foreach($_POST['id'] as $v){
				$group->delHostToGroup($v,$_GET['gid']);
			}
			echo "<script>alert('组成员已移出');window.location.go(-1);</script>";
		}		
	}
}
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
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='group.php'>服务器组</a></strong> / <small>主机</small></div>
    </div>

    <div class="am-g">
      <div class="am-u-md-6 am-cf">
        <div class="am-fl am-cf">
          <div class="am-btn-toolbar am-fl">
            <div class="am-btn-group am-btn-group-xs">
		<div>组中列表</div>
            </div>
          </div>
        </div>
      </div>
    
    </div>

    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form" name='removegroup' method='POST' action=''>
		<input type='hidden' name='action' value='removegroup'>
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">IP</th><th class="table-type">主机名</th><th class="table-type">操作系统</th><th class="table-type">CPU</th><th class="table-type">内存</th><th class="table-type">硬盘</th><th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
<?php
if(is_array($data)){
	$i=1;
	foreach($data as $k=>$v){
?>
            <tr>
              <td><input type="checkbox" name='id[]' value=<?php echo $v['id']; ?> ></td>
              <td><?php echo $i; ?></td>
              <td><a href="hostdetail.php?hid=<?php echo $v['id']; ?>"><?php echo $v['ip']; ?></a></td>
              <td><?php echo $v['hostname']; ?></td>
              <td><?php echo $v['os']; ?></td>
              <td><?php echo $v['cpu']; ?></td>
              <td><?php echo $v['ram']; ?></td>
              <td><?php echo $v['hd']; ?></td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><a href='hostdetail.php?hid=<?php echo $v['id'];?>'><span class="am-icon-pencil-square-o"></span>编辑</a></button>
                    <button class="am-btn am-btn-default am-btn-xs am-text-danger"><a href='hostdetail.php?action=delete&hid=<?php echo $v['id'];?>'><span class="am-icon-trash-o"></span>删除</a></button>
                  </div>
                </div>
              </td>
            </tr>
<?php

		$i++;
	}
}
?>

          </tbody>
        </table>

   <div class="am-margin">
    <button type="submit" class="am-btn am-btn-primary am-btn-xs">移出</button>
    <button type="reset" class="am-btn am-btn-primary am-btn-xs">重置</button>
  </div>
</form>
</div>
          <hr />
<div>组外列表</div>
 <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form" name='addgroup' method='POST' action=''>
		<input type='hidden' name='action' value='addgroup'>
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">IP</th><th class="table-type">主机名</th><th class="table-type">操作系统</th><th class="table-type">CPU</th><th class="table-type">内存</th><th class="table-type">硬盘</th><th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
<?php
if(is_array($data2)){
	$i=1;
	foreach($data2 as $k=>$v){
?>
            <tr>
              <td><input type="checkbox" name='id[]' value=<?php echo $v['id']; ?> ></td>
              <td><?php echo $i; ?></td>
              <td><a href="hostdetail.php?hid=<?php echo $v['id']; ?>"><?php echo $v['ip']; ?></a></td>
              <td><?php echo $v['hostname']; ?></td>
              <td><?php echo $v['os']; ?></td>
              <td><?php echo $v['cpu']; ?></td>
              <td><?php echo $v['ram']; ?></td>
              <td><?php echo $v['hd']; ?></td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><a href='hostdetail.php?hid=<?php echo $v['id'];?>'><span class="am-icon-pencil-square-o"></span>编辑</a></button>
                    <button class="am-btn am-btn-default am-btn-xs am-text-danger"><a href='hostdetail.php?action=delete&hid=<?php echo $v['id'];?>'><span class="am-icon-trash-o"></span>删除</a></button>
                  </div>
                </div>
              </td>
            </tr>
<?php

		$i++;
	}
}
?>

          </tbody>
        </table>
  <div class="am-margin">
    <button type="submit" class="am-btn am-btn-primary am-btn-xs">增加</button>
    <button type="reset" class="am-btn am-btn-primary am-btn-xs">重置</button>
  </div>
          
</form>
      </div>

    </div>
  </div>
  <!-- content end -->


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
