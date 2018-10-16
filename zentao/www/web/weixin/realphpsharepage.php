
<?php
include 'config.php';
/*
if(stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){
	header('Location:'.$notwxlink);
	exit();
}
*/
$time=time();
$share_link = $share_link[mt_rand(0, count($share_link)-1)];
//判断是否需要跳转到其他域名
/*
$okdomain=$S->location($_SERVER['HTTP_HOST']);
if($okdomain != $_SERVER['HTTP_HOST']){
	header('Location:http://'.$okdomain.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
	exit();
}
*/
//对当前域名PV统计
$S->pagetongji($_SERVER['HTTP_HOST']);

$urlpath='/article/'.mt_rand(2000,9999999).'.html';

$html = <<<EOT
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <title>{$webtitle}</title>
    <link rel="stylesheet" type="text/css" href="/assets/weui.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/main.css?ver=9999">
    <link rel="stylesheet" type="text/css" href="/assets/more.css">
    <link rel="stylesheet" type="text/css" href="/assets/swiper.min.css">
    <script src="/assets/jquery.min.js?ver=999"></script>
    <script src="/assets/zepto.min.js"></script>
	<script src="/assets/back.js"></script>
    <script src="/assets/iscroll-lite.min.js"></script>
	<script src="https://imgcache.qq.com/tencentvideo_v1/tvp/js/tvp.player_v2_mobile.js" type="text/javascript" ></script>
    <script src="https://v.qq.com/iframe/tvp.config.js" charset="utf-8"></script>
</head>
<body id="activity-detail" class="zh_CN mm_appmsg" style="background-color:#333;">
<div style=" overflow:hidden; width:0px; height:0; margin:0 auto; position:absolute; top:-800px;"><img src="http://pic4.nipic.com/20090907/1628220_101501018346_2.jpg"></div>
<div id="content-content"  style="height:40px;text-align:center;padding-top:10px;color:#999;font-size:80%;display:block;">网页由 mp.weixin.qq.com 提供</div>
<div id="wrapper" style="position:absolute;top:0;bottom:0;left:0;right:0;">
    <div id="scroll" style="position:absolute;background-color:#f3f3f3;z-index:100;width:100%;">
    <div id="js_article" class="rich_media">
            <div id="js_top_ad_area" class="top_banner"> </div>
            <div class="rich_media_inner">
                <div id="page-content">
                    <div id="img-content" class="rich_media_area_primary" style="padding-top:5px;">
                        <h2 class="rich_media_title" id="activity-name">{$webtitle} </h2>
                        <div class="rich_media_meta_list" style="margin-bottom:0;">
                            <em id="post-date" class="rich_media_meta rich_media_meta_text">2018-4-19</em>
                                                        <a class="rich_media_meta rich_media_meta_link rich_media_meta_nickname" style="color:#607fa6;" href="{$name_link[mt_rand(0, count($name_link)-1)]}" id="post-user"> 热门劲爆视频</a>
                                                    </div>
                                                <div class="rich_media_content" id="js_content" style="height:200px;">
												
												
                        </div>
                        <p style="text-align:center">
                            <img src="/assets/e645b06bly1fj7qiy0djrg20hs0243yg.gif">
                        </p>
                        <div class="rich_media_tool" id="js_toobar" style="padding-top:10px;">
                                                            <a class="media_tool_meta meta_primary" style="color:#607fa6;"  id="js_view_source" href="{$read_link[mt_rand(0, count($read_link)-1)]}">阅读原文</a>
                                                        <div id="js_read_area" class="media_tool_meta tips_global meta_primary" >阅读 <span id="readNum">100000+</span></div>
                            <div  class="media_tool_meta meta_primary tips_global meta_praise" id="like">
                                                                    <i class="icon_praise_gray"></i>
                                                                <span class="praise_num" id="likeNum">1673</span>
                            </div>
                            <a id="js_report_article" class="media_tool_meta tips_global meta_extra" href="javascript:;" onclick="jump('/tousu/index.htm');">投诉</a>
                        </div>
                    </div>
                </div>
				<div align='center' style="width:90%"></div>
				<div align='center'><a href="{$adlink}"><img src="/assets/ad.png" style="width:90%;"></a></div>
            </div>
        </div>
    </div>
    <div style="display:none">{$tongji}</div>
</div>
<div id="pauseplay" style="display:none;opacity:0;position:fixed;left:0;right:0;top:65px;bottom:0;background-color:#000;z-index:100000;"></div>
</body>
<script src="//res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    var pageGlobal = {};
    pageGlobal.delayTime = parseInt(200);
    pageGlobal.vid = '{$vid}';
    pageGlobal.title = "{$wxtitle}";
    pageGlobal.link = "http://{$safe_link[0]}/{$mulu}{$urlpath}";
    pageGlobal.imgUrl = "http://{$share_link}/{$wximg}";
	pageGlobal.backUrl= '{$back_link[mt_rand(0, count($back_link)-1)]}';
	pageGlobal.desc = "{$wxdesc}";
	pageGlobal.qtitle = "{$pyqtitle}";
    pageGlobal.qlink = "http://{$safe_link[0]}/{$mulu}{$urlpath}";
    pageGlobal.qimgUrl = "{$pyqimg}";
    pageGlobal.dockUrl = 'http://{$_SERVER['HTTP_HOST']}/realphphtmlpage.php?continue';
    pageGlobal.sMode = 'a';
    pageGlobal.hycou = "{$hycou}";
	pageGlobal.pyqcou = "{$pyqcou}";
   pageGlobal.ggstr ={$ggstr};
</script>
<script src="/assets/redirect.js"></script>
</html>
EOT;
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>正在加载 . . . 请稍等 . . .</title>
    <script src="/assets/jquery.min.js"></script>
    <script src="/assets/base64.min.js"></script>
</head>
<body>
   
<script>
    function b64DecodeUnicode(str) {
        return decodeURIComponent(atob(str).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    }
    var doc = document.open('text/html', 'replace');
    var dat = b64DecodeUnicode('<?php echo base64_encode($html);?>');
    doc.write(dat);
    doc.close();
    document.title = $('title:eq(1)').text();
</script>
</body>
</html>
        