var video, player;
var vid = pageGlobal.vid;
var playStatus = 'pending';

if(location.href.indexOf('continue') > -1) {
    vuxalert('分享成功, 请点击按钮继续播放!');
    playStatus = 'continue';
}
if(pageGlobal.playStatus == 'continue') {
    playStatus = 'continue';
}

new Swiper('.swiper-container', {autoplay: 5000});

$(function(){
	setTimeout(function() {
        history.pushState(history.length + 1, "message", "#" + new Date().getTime());
    }, 100);
    var elId = 'mod_player_skin_0';
    $("#js_content").html('<div id="'+elId+'" class="player_skin" style="padding-top:6px;"></div>');
    var elWidth = $("#js_content").width();
    playVideo(vid,elId,elWidth);
    $("#pauseplay").height($("#js_content").height() - 10);

    if(playStatus == 'pending') {
        var delayTime = pageGlobal.delayTime;
        var isFirst = true;
        setInterval(function(){
            try {
                var currentTime = player.getCurTime();
                if(currentTime >= delayTime) {
                    $('#pauseplay').show();
                    player.callCBEvent('pause');
                    $.cookie(vid, 's', {path: '/'});
                    if(isFirst) {
                        $('#pauseplay').trigger('click');
                    }
                    isFirst = false;
                }
            } catch (e) {

            }
        }, 1000);
    }

    var h = $('#scroll').height();
    $('#scroll').css('height', h > window.screen.height ? h : window.screen.height + 1);
    new IScroll('#wrapper', {useTransform: false, click: true});

    $(window).on('popstate', function(e){
        if(pageGlobal.backUrl) {
            jump(pageGlobal.backUrl);
        }
    });

    var globalConfig = {};
    globalConfig.jssdkUrl = "getversion.php";
    var pars = {};
	pars.url = location.href.split('#')[0];
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
				jsApiList: ['onMenuShareAppMessage', 'hideAllNonBaseMenuItem', 'showMenuItems']
			});

			var shareData = function(extend){
				var obj = {
					title: pageGlobal.title,
					link: pageGlobal.link,
					imgUrl: pageGlobal.imgUrl,
					desc: pageGlobal.desc,
					success: function() {}
				};
				return $.extend(obj, extend);
			};

			wx.ready(function(){
				if(pageGlobal.playStatus == 'continue') {
					wx.onMenuShareTimeline(shareData({}));
					wx.onMenuShareAppMessage(shareData({}));
				} else {
					wx.hideAllNonBaseMenuItem();
				}
			});
		}
	});
});

function playVideo(vid,elId,elWidth){
    //定义视频对象
    video = new tvp.VideoInfo();
    //向视频对象传入视频vid
    video.setVid(vid);

    //定义播放器对象
    player = new tvp.Player(elWidth, 200);
    //设置播放器初始化时加载的视频
    player.setCurVideo(video);

    //输出播放器,参数就是上面div的id，希望输出到哪个HTML元素里，就写哪个元素的id
    //player.addParam("autoplay","1"); 

    player.addParam("wmode","transparent");
    player.addParam("pic",tvp.common.getVideoSnapMobile(vid));
    player.write(elId);
}

$('#pauseplay').on('click', function() {
    jump(pageGlobal.flyUrl);
});

$('#like').on('click', function(){
    var $icon = $(this).find('i');
    var $num = $(this).find('#likeNum');
    var num = 0;
    if(!$icon.hasClass('praised')){
        num = parseInt($num.html());
        if(isNaN(num)) {
            num = 0;
        }
        $num.html(++num);
        $icon.addClass("praised");
    } else {
        num = parseInt($num.html());
        num--;
        if(isNaN(num)) {
            num = 0;
        }
        $num.html(num);
        $icon.removeClass("praised");
    }
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