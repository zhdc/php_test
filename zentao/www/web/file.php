<?php
require_once 'include/ssh.class.php';
require_once 'include/host.class.php';
$host=new host();

?>
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
 <div class="admin-content">
     <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='file.php'>配置更改</a></strong> / <small>更改</small></div>
    </div>
<?php
if($_SERVER['REQUEST_METHOD']=='POST' AND $_GET['step']==2){
	$result=$host->getHostList($_POST['id']);
	$ssh=new ssh($result[0]['ip'],$result[0]['os']);
	if(empty($_POST['file']) & !empty($_POST['dir'])){
		echo nl2br($ssh->execute($_POST['dir']));
	}else{
	$data=$ssh->getfile(str_replace('\\','/',$_POST['file']));
?>
			<form action='?step=3' method='POST'>
			<input type='hidden' name='os' value='<?php echo $result[0]['os'];?>'>
			<div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              服务器
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type='text' readonly='readonly' name='ip' value='<?php echo $result[0]['ip'];?>' >
            </div>
          </div>
			<div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              文件路径
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type='text' readonly='readonly' name='file' value='<?php echo str_replace('\\','/',$_POST['file']);?>' >
            </div>
          </div>
			
			<div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              文件内容
            </div>
            <div class="am-u-sm-4 am-u-end">
			<textarea rows="20" cols='100' name='content'><?php echo $data; ?></textarea>
            </div>
            </div>
			<div class="am-margin">
				<button type="submit" class="am-btn am-btn-primary am-btn-xs">保存</button>
				<button type="reset" class="am-btn am-btn-primary am-btn-xs">重置</button>
			</div>
		    </form>
<?php
	}
}elseif($_SERVER['REQUEST_METHOD']=='POST' AND $_GET['step']==3){
	$ssh=new ssh($_POST['ip'],$_POST['os']);
	if($data=$ssh->savefile($_POST['file'],$_POST['content'])){
		echo "<script>alert('文件保存成功');window.location.go(-1);</script>";
	}
	
}
else{
?>
  <!-- content start -->

        <form class="am-form" method="POST" action='?step=2'>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              主机
            </div>
            <div class="am-u-sm-4 am-u-end">
              <select name='id'>
				<?php
				$data=$host->getHostList();
				if(is_array($data)){
					foreach($data as $k=>$v){
						echo "<option value=".$v['id'].">".$v['ip']."</option>";
					}
				}
			?>
			  </select>
            </div>
          </div>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              文件路径
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text"  class="am-input-sm" name='file' value='' placeholder="请输入文件路径以进行编辑" >
            </div>
          </div>
		  
		  <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              命令
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text"  class="am-input-sm" name='dir' value='' placeholder="请输入命令查看文件夹内容">
            </div>
          </div>
		
		<div class="am-margin">
			<button type="submit" class="am-btn am-btn-primary am-btn-xs">提交</button>
			<button type="reset" class="am-btn am-btn-primary am-btn-xs">重置</button>
		</div>
		</form>
		
		
<?php
}
?>