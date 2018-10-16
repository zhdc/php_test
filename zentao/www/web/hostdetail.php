<?php
require_once 'include/host.class.php';
$host=new host();
if($_GET['hid'] && $_GET['action']=='delete'){
        $host->delHost($_GET['hid']);
	echo "<script>alert('主机删除成功');window.location.go(-1);</script>";
}elseif($_GET['hid']){
	$data=$host->getHost($_GET['hid']);
	$action='updateHost';
}else{
	$action='addHost';
}

if($_SERVER['REQUEST_METHOD']=='POST'){
	if($_POST['action']=='updateHost'){
		$data=$_POST;
		unset($data['action']);
		unset($data['note']);
		unset($data['id']);
		unset($data['ip']);
		if($host->updateHost($_GET['hid'],$data)){
			echo "<script>alert('修改成功')</script>";
		}
	}elseif($_POST['action']=='addHost'){
		if($host->addHost($_POST['ip'],$_POST['hostname'],$_POST['os'],$_POST['cpu'],$_POST['ram'],$_POST['hd'],$_POST['note'])){
			echo "<script>alert('新增成功')</script>";
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
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href ='?'>主机组</a></strong> / <small>主机详细信息</small></div>
    </div>
	
<?php
if($_GET['action']=='addHost' OR $_GET['hid']){
?>
      <form class="am-form" method="POST" action=''>
	  <input type="hidden" name="action" value="<?php echo $action; ?>"/>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              IP
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text" <?php if($action!='addHost'){echo 'readonly="readonly"';};?>  class="am-input-sm" name='ip' value='<?php echo $data[0]['ip']; ?>'>
            </div>
          </div>

          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              主机名
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text" class="am-input-sm" name='hostname' value='<?php echo $data[0]['hostname']; ?>'>
            </div>
          </div>

          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              操作系统
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text" class="am-input-sm" name='os' value='<?php echo $data[0]['os']; ?>'>
            </div>
          </div>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              CPU
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text" class="am-input-sm" name='cpu' value='<?php echo $data[0]['cpu']; ?>'>
            </div>
          </div>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              内存
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text" class="am-input-sm" name='ram' value='<?php echo $data[0]['ram']; ?>'>
            </div>
          </div>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              硬盘
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text" class="am-input-sm" name='hd' value='<?php echo $data[0]['hd']; ?>'>
            </div>
          </div>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              备注
            </div>
            <div class="am-u-sm-4 am-u-end">
			<textarea rows="4" name='note'><?php echo $data[0]['note']; ?></textarea>
            </div>
          </div>

			<div class="am-margin">
				<button type="submit" class="am-btn am-btn-primary am-btn-xs">保存</button>
				<button type="reset" class="am-btn am-btn-primary am-btn-xs">重置</button>
			</div>
        </form>
</div>
<?php
}else{
?>
        <div class="am-fl am-cf">
          <div class="am-btn-toolbar am-fl">
            <div class="am-btn-group am-btn-group-xs">
              <button type="button" class="am-btn am-btn-default"><a href='?action=addHost'><span class="am-icon-plus"></span> 新增</a></button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>
            </div>
          </div>
        </div>
    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-id">ID</th><th class="table-title">IP</th><th class="table-type">操作系统</th><th class="table-title">主机名</th><th class="table-title">CPU</th><th class="table-title">硬盘</th><th class="table-title">内存</th><th class="table-set">备注</th><th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
<?php
$data=$host->getHostList();
if(is_array($data)){
	$i=1;
	foreach($data as $k=>$v){
?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><a href="?hid=<?php echo $v['id']; ?>"><?php echo $v['ip']; ?></a></td>
			  <td><?php echo $v['os']; ?></td>
              <td><?php echo $v['hostname']; ?></td>
			  <td><?php echo $v['cpu']; ?></td>
			  <td><?php echo $v['hd']; ?></td>
			  <td><?php echo $v['ram']; ?></td>
              <td><?php echo $v['note']; ?></td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><a href="?action=edit&hid=<?php echo $v['id']; ?>"><span class="am-icon-pencil-square-o"></span>编辑</a></button>
                    <button class="am-btn am-btn-default am-btn-xs am-text-danger"><a href="?action=delete&hid=<?php echo $v['id']; ?>"><span class="am-icon-trash-o"></span> 删除</a></button>
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
<?php
}
?>
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
