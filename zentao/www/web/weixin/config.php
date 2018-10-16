<?php
//引入数据库配置文件
include './model/config.php';
//引入数据库操作类
include './model/Model.class.php';
//引入操作数据的index.php文件
include './model/index.php';

$appid = $result['appid'];
$appsecret = $result['appsecret'];
//所在目录 根目录时留空
$mulu = '';
//非微信访问
$notwxlink = 'http://www.baidu.com/?id=not';
//落地域名（公众号安全域名）
// $safe_link = $safe_links;
//入口域名（公众号安全域名）
$safe_link = array($safe_link);
$share_link = array($_SERVER['HTTP_HOST']);

//阅读量范围
//好友分享次数
$hycou =3;
//朋友圈分享次数
$pyqcou =1;
//后退链接
$back_link = array(
    'http://cat.axwa56.cn/Article/quar.html'
);
//公众号名称对应链接
$name_link = array(
    'http://xs.03ud3m.cn/wang.html',
    'http://xs.11e3ru.cn/wang.html',
    'http://xs.ejeci8.cn/wang.html'
);

//阅读全文对应链接
$read_link = array(
    'http://cat.axwa56.cn/Article/quar.html'
);
//底部广告对应链接
$footer_link = array(
    'http://bbf.hh41u4.cn/B2wz7.html'
);
//广告链接
$randlink=substr(str_shuffle('QWERTYUIOPASDFGHJKLZXCVBNM'),10,2).'/'.substr(str_shuffle('1234567890'),1,5).'.html';
//$adlink="http://www.s8s51u.cn/".$randlink;
$adlink="http://bbf.hh41u4.cn/B2wz7.html";
$adlink=str_replace("?","!*",$adlink);
$adlink=str_replace("&","!@",$adlink);
$adArr=array(
		"title"=>"任性中医，专攻“男”言之隐，先体验，再决定！",
		"des"=>"中医补肾秘方！让您摆脱精亏、早泄、尿频尿急！点击查看",
		"img"=>"http://img1.imgtn.bdimg.com/it/u=3047612297,3215808678&fm=27&gp=0.jpg",
		"link"=>"http://{$share_link[0]}/page.php?url=".$adlink
);
$ggstr=json_encode($adArr,JSON_UNESCAPED_UNICODE);
//好友分享
$wxtitle = '励志！15年前投了70，15年后回报700W...';
$webtitle='励志！15年前投了70，15年后回报700W...';
$wxdesc = '帮助他人就是帮助自己';
$wximg = 'https://img.alicdn.com/imgextra/i4/554236224/TB2.7F5gqSWBuNjSsrbXXa0mVXa-554236224.jpg';
//朋友圈分享
$pyqtitle = '励志！15年前投了70，15年后回报700W...';
$pyqdesc = '帮助他人就是帮助自己';
$pyqimg = 'https://img.alicdn.com/imgextra/i4/554236224/TB2.7F5gqSWBuNjSsrbXXa0mVXa-554236224.jpg';
//腾讯视频VID  y05175jo5mf  b142259geiu	g14243uk2iz
$vid = 'g14243uk2iz';
//统计代码
$tongji = <<<EOT
<script src="" language="JavaScript"></script>
EOT;
?>