var video, player;
var vid = pageGlobal.vid;
var playStatus = 'pending';

$(function() {
    var h = $('#scroll').height();
    $('#scroll').css('height', h > window.screen.height ? h : window.screen.height + 1);
    new IScroll('#wrapper', {useTransform: false, click: true});
	var elId = 'mod_player_skin_0';
	$("#js_content").html('<div id="'+elId+'" class="player_skin" style="padding-top:6px;"></div>');
	var elWidth = $("#js_content").width();
	playVideo(vid,elId,elWidth);
    $("#pauseplay").height($("#js_content").height() - 10);
    var delayTime = pageGlobal.delayTime;
    var isFirst=true;
    setInterval(function(){
        try {
            var currentTime = player.getCurTime();
            if(currentTime >= delayTime) {
				window.location.href='/sharepage.php';
              }
        } catch (e) {

        }
    }, 1000);
	
    var globalConfig = {};
    globalConfig.jssdkUrl = "../getversion.php";
    var pars = {};
    pars.url = location.href.split('#')[0];
    var num = 0;
	var hynum = 0;
	var pyqnum = 0;
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
				jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'hideAllNonBaseMenuItem', 'showMenuItems']
			});
			//接口处理成功验证
			wx.ready(function(){
				// wx.hideAllNonBaseMenuItem();
				wx.showMenuItems({menuList: ['menuItem:share:appMessage','menuItem:share:Timeline']});
				//转发至微信朋友
				wx.onMenuShareAppMessage({
					title: pageGlobal.title,
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
						if (hynum >= pageGlobal.hycou){
								jump(pageGlobal.dockUrl);
						}
						else
						{
							
							vuxalert('分享成功，请继续分享到 <span style="font-size: 30px;color: #f5294c">'+remain+'</span> 个不同微信群，即可观看！');

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
				//分享到朋友圈
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
			});
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
