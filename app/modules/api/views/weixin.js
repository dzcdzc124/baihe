(function(window, document, wx){
    wx.config({});

    function refreshShareData() {
        var data = window.shareData;
        if (typeof data != 'object') return false;

        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: data.timelineTitle || data.title,
                link: data.timelineUrl || data.url,
                imgUrl: data.timelinePicUrl || data.picUrl,
                success: function () {
                    if (typeof data.callback == 'function') {
                        data.callback('ShareTimeline');
                    }
                },
                cancel: function () {}
            });

            wx.onMenuShareAppMessage({
                title: data.messageTitle || data.title,
                desc: data.description,
                link: data.messageUrl || data.url,
                imgUrl: data.messagePicUrl || data.picUrl,
                type: '',
                dataUrl: '',
                success: function () {
                    if (typeof data.callback == 'function') {
                        data.callback('ShareAppMessage');
                    }
                },
                cancel: function () {}
            });

            if (typeof data.extra == 'function') {
                data.extra(wx);
            }
        });
    }

    refreshShareData();
    window.refreshShareData = refreshShareData;
})(window, document, wx);