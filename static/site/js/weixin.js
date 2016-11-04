var ua = navigator.userAgent.toLowerCase();
var WXversion = ua.match(/micromessenger/) ? ua.match(/micromessenger\/([\d.]+)/)[1] : null;

/* _hmt.push(['_trackEvent', "weixin", "view"]);
 _czc.push(["_trackEvent", "weixin", "view"]);*/

//自定义微信分享
window.shareData = {
	picUrl: basePath + "/img/400.jpg",
    url: baseLink,
	title: "爱情实验室",
	desc: "权威测试题，让你读懂彼此的心，避开爱情陷阱，找到那个对的人。",
    timelineTitle : "权威测试题，让你读懂彼此的心，避开爱情陷阱，找到那个对的人。",
	callback: function(type) {
        if(typeof _hmt != "undefined"){
	       _hmt.push(['_trackEvent', "weixin", type]);
        }
	}
};


function refreshShareData() {
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

        window.chooseWXPay = function(param, callback){
            wx.chooseWXPay({
                timestamp: param["timeStamp"], // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                nonceStr: param["nonceStr"], // 支付签名随机串，不长于 32 位
                package: param["package"], // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                signType: param["signType"], // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                paySign: param["paySign"], // 支付签名
                success: function (res) {
                    // 支付成功后的回调函数
                    callback(res);
                },
                fail: function(res){
                    callback(res);
                },
                cancel: function (res) { 
                    // 用户取消分享后执行的回调函数
                    callback(res);
                }
            });
        }
    });
    
}
refreshShareData();