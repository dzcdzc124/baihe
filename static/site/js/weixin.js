var ua = navigator.userAgent.toLowerCase();
var WXversion = ua.match(/micromessenger/) ? ua.match(/micromessenger\/([\d.]+)/)[1] : null;

/* _hmt.push(['_trackEvent', "weixin", "view"]);
 _czc.push(["_trackEvent", "weixin", "view"]);*/

//自定义微信分享
window.shareData = {
	picUrl: basePath + "/img/400.jpg",
    url: baseLink,
	title: "vivo暑期超值送-7待你来",
	desc: "凡在暑期购买vivoX7&X7Plus的用户均可参与购机抽奖活动，每日一台手机大奖等你拿",
    timelineTitle : 'vivo暑期超值送-7待你来，每日一台X7Plus大奖等你拿',
	callback: function(type) {
        if(typeof _hmt != "undefined"){
	       _hmt.push(['_trackEvent', "weixin", type]);
        }
        if(typeof _czc != "undefined"){
            _czc.push(["_trackEvent", "weixin", type]);
        }
	}
};


function refreshShareData() {
    if (WXversion >= '6.0.2') {
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: window.shareData.timelineTitle,
                link: window.shareData.url,
                imgUrl: window.shareData.picUrl,
                success: function () { 
                    shareData.callback("ShareTimeline");
                },
                cancel: function () {}
            });

            wx.onMenuShareAppMessage({
                title: window.shareData.title,
                desc: window.shareData.desc,
                link: window.shareData.url,
                imgUrl: window.shareData.picUrl,
                type: '',
                dataUrl: '',
                success: function () { 
                    shareData.callback("ShareAppMessage");
                },
                cancel: function () {}
            });

            wx.onMenuShareQQ({
			    title: window.shareData.title,
			    desc: window.shareData.desc,
			    link: window.shareData.url,
			    imgUrl: window.shareData.picUrl,
			    success: function () { 
			       shareData.callback("ShareQQ");
			    },
			    cancel: function () { 
			       // 用户取消分享后执行的回调函数
			    }
			});
			wx.onMenuShareWeibo({
			    title: window.shareData.title,
			    desc: window.shareData.desc,
			    link: window.shareData.url,
			    imgUrl: window.shareData.picUrl,
			    success: function () { 
			       shareData.callback("ShareWeibo");
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});

            window.scanQRCode = function(callback){
                wx.scanQRCode({
                    needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                    success: function (res) {
                        var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                        callback(result);
                    }
                });
            }
        });
    } else {
        (function() {
            function onBridgeReady() {
                WeixinJSBridge.call('hideToolbar');
                WeixinJSBridge.call('showOptionMenu');
                WeixinJSBridge.on('menu:share:timeline', function(argv){
                    WeixinJSBridge.invoke('shareTimeline',{
                        "img_url"    : window.shareData.picUrl,
                        "img_width"  : "400",
                        "img_height" : "400",
                        "link"       : window.shareData.url,
                        "desc"       : window.shareData.desc,
                        "title"      : window.shareData.title
                    }, function(res){
                        shareData.callback("ShareTimeline");
                    });
                });

                WeixinJSBridge.on('menu:share:appmessage', function(argv){
                    WeixinJSBridge.invoke('sendAppMessage',{
                        "appid"      : appId,
                        "img_url"    : window.shareData.picUrl,
                        "img_width"  : "400",
                        "img_height" : "400",
                        "link"       : window.shareData.url,
                        "desc"       : window.shareData.desc,
                        "title"      : window.shareData.title
                    }, function(res){
                        shareData.callback("ShareAppMessage");
                    });
                });

                WeixinJSBridge.on('menu:share:weibo', function(argv){
                    WeixinJSBridge.invoke('shareWeibo',{
                        "content" : window.shareData.desc + window.shareData.url,
                        "url"     : window.shareData.url
                    }, function(res){
                        shareData.callback("ShareWeibo");
                    });
                });

                WeixinJSBridge.on('menu:share:facebook', function(argv){
                    WeixinJSBridge.invoke('shareFB',{
                          "img_url"    : window.shareData.picUrl,
                          "img_width"  : "640",
                          "img_height" : "640",
                          "link"       : window.shareData.url,
                          "desc"       : window.shareData.desc,
                          "title"      : window.shareData.title
                    }, function(res) {
                        shareData.callback("ShareFacebook");
                    });
                });

                WeixinJSBridge.on('menu:general:share', function(argv){
                    argv.generalShare({
                        "appid"      : appId,
                        "img_url"    : window.shareData.picUrl,
                        "img_width"  : "640",
                        "img_height" : "640",
                        "link"       : window.shareData.url,
                        "desc"       : window.shareData.desc,
                        "title"      : window.shareData.title
                    }, function(res){
                        shareData.callback("generalShare");
                    });
                });
            };

            document.addEventListener('WeixinJSBridgeReady', onBridgeReady);
        })();
    }
}
refreshShareData();