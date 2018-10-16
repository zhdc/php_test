<?php
require_once 'include/cmd.class.php';
require_once 'include/arg.class.php';
require_once 'include/host.class.php';
require_once 'include/ssh.class.php';
$cmd=new cmd();
$arg=new arg();
if($_GET['action']=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
	$res=addslashes(str_replace('\'',"\\\\'",$_POST['cmd']));
	$cid=$cmd->addCmd($res,$_POST['name'],$_POST['menu'],$_POST['note']);
	if(is_array($_POST['arg'])){
		foreach($_POST['arg'] as $v){
			$arg->addArgs($cid,$v[0],$v[1]);
		}
		echo "<script>alert('命令创建成功');window.location.go(-1);</script>";
	}
}elseif($_GET['cid'] && $_GET['action']=='edit' && $_SERVER['REQUEST_METHOD']=='POST'){
	$_POST['cmd']=addslashes(str_replace('\'',"\\\\\\\\'",$_POST['cmd']));
	$cmd->updateCmd($_GET['cid'],$_POST);
	echo "<script>alert('命令编辑成功');window.location.go(-1);</script>";
}elseif($_GET['action']=='run' && $_SERVER['REQUEST_METHOD']=='POST'){
	$host=new host();
	$cmd_data=$cmd->getCmdByID($_GET['cid']);
	$arg_data=$arg->getArgs($_GET['cid']);
	$host_arr=$host->getHost($_POST['hid']);
	$v=$host_arr[0];
	if(strstr(strtolower($v['os']),'wind')){
		${$v['ip']}=new ssh($v['ip'],'windows');
	}else{
		${$v['ip']}=new ssh($v['ip']);
	}
	unset($_POST['hid']);
	unset($_POST['cmd']);
	echo nl2br(${$v['ip']}->cmd($cmd_data[0]['cmd'],$_POST));
}elseif($_GET['cid'] && $_GET['action']=='delete'){
	if($cmd->delCmd($_GET['cid']) && $arg->delArg($_GET['cid'])){
		echo "<script>alert('命令及参数删除成功');window.location.go(-1);</script>";
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
if($_GET['action']=='edit' && $_GET['cid']){
	$data=$cmd->getCmdByID($_GET['cid']);
	$args=$arg->getArgs($_GET['cid']);
}
?>
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='?'>自定义命令</a></strong> / <small>命令</small></div>
    </div>
        <form class="am-form" method="POST" action=''>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              命令名称
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text"  class="am-input-sm" name='name' value='<?php echo $data[0]['name']; ?>'>
            </div>
          </div>
            <div class="am-g am-margin-top-sm">
                <div class="am-u-sm-2 am-text-right">
                    命令
                </div>
                <div class="am-u-sm-4 am-u-end">
                    <textarea rows="4" name='cmd'><?php echo $data[0]['cmd']; ?></textarea>
                    <div class="am-margin">
                        <script>var i = 1</script>
                        <input id="btn_addtr" type="button" class="am-btn am-btn-primary am-btn-xs" value="添加参数">
                    </div>
                </div>
            </div>

            <div class="am-g am-margin-top-sm">
                <div class="am-u-sm-2 am-text-right">
                    参数
                </div>
                <div class="am-u-sm-4 am-u-end">
                    <table id="dynamicTable" name="dynamicTable">
                        <tbody></tbody>
                    </table>
                </div>
            </div>

		  <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              菜单
            </div>
            <div class="am-u-sm-4 am-u-end">
              <input type="text"  class="am-input-sm" name='menu' value='<?php echo $data[0]['name']; ?>'>
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
//命令执行
}elseif($_GET['action']=='run' && $_GET['cid']){
	$host=new host();
	$cmd_data=$cmd->getCmdByID($_GET['cid']);
	$arg_data=$arg->getArgs($_GET['cid']);
?>
   <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='?'>自定义命令</a></strong> / <small>执行命令</small></div>
    </div>
        <form class="am-form" method="POST" action=''>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-2 am-text-right">
              主机选择
            </div>
            <div class="am-u-sm-4 am-u-end">
            <select name='hid'>
			<?php
				
				$data=$host->getHost();
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
              命令内容
            </div>
            <div class="am-u-sm-4 am-u-end">
			<input type='text' name='cmd' value='<?php echo $cmd_data[0]['cmd']; ?>' readonly='readonly' >
            </div>
          </div>
			<?php
				if(is_array($arg_data)){
					foreach($arg_data as $k=>$v){
			?>
			    <div class="am-g am-margin-top-sm">
                <div class="am-u-sm-2 am-text-right">
                    <?php echo $v['name']; ?>
                </div>
                <div class="am-u-sm-4 am-u-end">
				<input type='text' name='<?php echo $v['arg']; ?>' value='' placeholder='<?php echo $v['arg']; ?>'>
                </div>
            </div>
			<?php
					}
				}
			?>


  <div class="am-margin">
    <button type="submit" class="am-btn am-btn-primary am-btn-xs">执行</button>
    <button type="reset" class="am-btn am-btn-primary am-btn-xs">重置</button>
  </div>
        </form>
<?php	
}else{
//命令显示
?>
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href='?'>自定义命令</a></strong> / <small>命令列表</small></div>
    </div>
    <div class="am-g">
      <div class="am-u-md-6 am-cf">
        <div class="am-fl am-cf">
          <div class="am-btn-toolbar am-fl">
            <div class="am-btn-group am-btn-group-xs">
              <button type="button" class="am-btn am-btn-default"><a href='?action=add'><span class="am-icon-plus"></span> 新增</a></button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>
            </div>
          </div>
        </div>
      </div>
      
    </div>

    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form" action='flow.php' method='POST'>
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">命令名</th><th class="table-type">命令内容</th><th class="table-set">菜单</th><th class="table-set">备注</th><th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
<?php
$data=$cmd->getCmdList();
if(is_array($data)){
	$i=1;
	foreach($data as $k=>$v){
?>
            <tr>
              <td><input type="checkbox" name='cid[]' value='<?php echo $v['id'] ?>' ></td>
              <td><?php echo $i; ?></td>
              <td><a href="?action=edit&cid=<?php echo $v['id']; ?>"><?php echo $v['name']; ?></a></td>
              <td><?php echo $v['cmd']; ?></td>
              <td><?php echo $v['menu']; ?></td>
			  <td><?php echo $v['note']; ?></td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><a href="?action=run&cid=<?php echo $v['id']; ?>">执行</a></button>
					<button class="am-btn am-btn-default am-btn-xs am-text-secondary"><a href="?action=edit&cid=<?php echo $v['id']; ?>"><span class="am-icon-pencil-square-o"></span>编辑</a></button>
                    <button class="am-btn am-btn-default am-btn-xs am-text-danger"><a href="?action=delete&cid=<?php echo $v['id']; ?>"><span class="am-icon-trash-o"></span> 删除</a></button>
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
		<span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="submit">创建流程</button>
        </span>
          
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
    <script type="text/javascript">
        
        $(document).ready(function () {

            var show_count = 20;   //要显示的条数
            var count = 1;    //递增的开始值，这里是你的ID
            $("#btn_addtr").click(function () {

                var length = $("#dynamicTable tbody tr").length;
                //alert(length);
                if (length < show_count)    //点击时候，如果当前的数字小于递增结束的条件
                {
                    var num = length + 1;
                    var operation_html = "<tr><td><div class='am-u - sm - 2 am-text - right'>参数名:<input type='text' name='arg[" + num + "][0]' value=''/>参数备注:<input type='text' name='arg[" + num + "][1]' value=''/></div> <div class='am- u - sm - 4 am- u - end'><button type='reset' name='del" + num + "' class='am-btn am-btn-primary am-btn-xs' onclick='deltr(this)'>删除参数</button></div> </td></tr>";
                    $("#dynamicTable tbody").append(operation_html);
                    changeIndex();//更新行号
                }
            });
        });

        function changeIndex() {
            var i = 1;
            $("#dynamicTable tbody tr").each(function () { //循环tab tbody下的tr
                $(this).find("input[name='NO']").val(i++);//更新行号
            });
        }

        function deltr(opp) {
            var length = $("#dynamicTable tbody tr").length;
            //alert(length);
                $(opp).parent().parent().parent().remove();//移除当前行
                changeIndex();
        }
    </script>
<!--<![endif]-->
<script src="assets/js/app.js"></script>
</body>
</html>
