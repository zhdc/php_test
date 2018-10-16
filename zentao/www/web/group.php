<?php
require_once 'include/group.class.php';
$group=new group();
if($_GET['action']=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
	$group->addGroup($_POST['name'],$_POST['note']);
	echo "<script>alert('创建服务器组成功!');</script>";
}elseif($_GET['gid'] && $_GET['action']=='edit' && $_SERVER['REQUEST_METHOD']=='POST'){
	$group->updateGroup($_GET['gid'],$_POST);
	echo "<script>alert('主机编辑成功');window.location.go(-1);</script>";
}elseif($_GET['gid'] && $_GET['action']=='delete'){
	if($group->delGroup($_GET['gid'])){
		echo "<script>alert('组删除成功');window.location.go(-1);</script>";
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

<?php
if($_GET['action']=='add' || $_GET['action']=='edit'){
$data=array();
if($_GET['action']=='edit' && $_GET['gid']){
	$data=$group->getGroup($_GET['gid']);
}
?>
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='group.php'>服务器组</a></strong> / <small>创建组</small></div>
    </div>
        <form class="am-form" method="POST" action=''>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              组名
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text"  class="am-input-sm" name='name' value='<?php echo $data[0]['name']; ?>'>
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
<?php
}else{
?>
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='group.php'>服务器组</a></strong> / <small>主机列表</small></div>
    </div>
    <div class="am-g">
      <div class="am-u-md-6 am-cf">
        <div class="am-fl am-cf">
          <div class="am-btn-toolbar am-fl">
            <div class="am-btn-group am-btn-group-xs">
              <button type="button" class="am-btn am-btn-default"><a href='group.php?action=add'><span class="am-icon-plus"></span> 新增</a></button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>
            </div>
          </div>
        </div>
      </div>
     
    </div>

    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">服务器组名</th><th class="table-type">主机数</th><th class="table-set">备注</th><th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
<?php
$data=$group->getGroupList();
if(is_array($data)){
	$i=1;
	foreach($data as $k=>$v){
?>
            <tr>
              <td><input type="checkbox" value=<?php echo $v['id']; ?>/></td>
              <td><?php echo $i; ?></td>
              <td><a href="groupdetail.php?gid=<?php echo $v['id']; ?>"><?php echo $v['name']; ?></a></td>
              <td><?php echo $v['count']; ?></td>
              <td><?php echo $v['note']; ?></td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><a href="group.php?action=edit&gid=<?php echo $v['id']; ?>"><span class="am-icon-pencil-square-o"></span>编辑</a></button>
                    <button class="am-btn am-btn-default am-btn-xs am-text-danger"><a href="group.php?action=delete&gid=<?php echo $v['id']; ?>"><span class="am-icon-trash-o"></span> 删除</a></button>
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
          
        </form>
      </div>

    </div>
<?php
}
?>
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
