/*
 * 预加载类，支持图片和音频预加载
 * file_list  需预加载的文件列表
 * process    加载过程函数，参数为进度
 * callback   加载完成后的回调函数
 * extra      非必须，表示需预加载的接口数
 *
*/
function preLoad( option ){
  this.startTime =  0;        //开始时间
  this.endTime =  0;          //结束时间
  this.progress =  0;
  this.load_timer =  null;
  this.loadedNum =  0;
  this.list = option.file_list;
  this.process = option.process?option.process:null;
  this.callback = option.callback;
  this.extra = option.extra ? option.extra: 0;
  if(this.list.length == 0){
    this.loadComplete();
  }else{
    this.init();  
  }
  
}
preLoad.prototype = {
  init:function(){
    //页面图片的加载
    this.startTime = Date.now();

    for(var i = 0 ;i < this.list.length; i++){
      var suffix = this.list[i].substr( this.list[i].lastIndexOf('.')+1 );
      if( $.inArray( suffix, ['mp3','wav','ogg'] ) > -1){
        this.loadAudio(this.list[i]);  
      }else{
        this.loadImg(this.list[i]);
      }
    }

    clearInterval(this.load_timer);
    this.load_timer = setInterval((function(_this){
      if(_this.process){
        return function(){
          _this.loading_count();
          _this.process(_this.progress);
        }
      }else{
        return function(){
          _this.loading_count();
        }
      }
    }(this)),40);
  },
  loadAudio: function(src){
    var audio = document.getElementById('audio') || document.createElement('audio');
    var self = this;
    audio.addEventListener("error", function(){
      self.loadedNum ++;
      console.log("Load Error: " + this.src);
    })
    //浏览器判断 当媒介能够以当前速率无需因缓冲而停止即可播放至结尾时触发 (chrome中会根据下载速度和播放速率之差进行计算，如果下载速度大于播放速率，会立刻触发该事件 )
    audio.addEventListener("canplaythrough", function(){
      self.loadedNum ++;
      //this.play();
    })
    //audio.pause();
    audio.src = basePath + src;
  },
  loadImg: function(src){
    var img = new Image();
    var self = this;
    img.onload = (function (_this) {
      return function(){
        _this.loadedNum ++;
      }
    }(this))
    img.onerror = (function (_this) {
      return function(){
        _this.loadedNum ++;
        console.log("Load Error: " + this.src);
      }
    }(this))
    img.src = src;
  },
  loading_count:function(){
    var np = Math.round( (this.loadedNum / (this.list.length + this.extra))*100 );
    if(np >= 100){
      this.progress += 20;

    }else if(this.progress < np || this.progress < 38){
      this.progress += 2;
    }
    
    if(this.progress >= 100){
      this.progress = 100;
      clearInterval(this.load_timer);
      this.loadComplete();
    }
  },
  loadComplete:function(){
    this.callback();
    this.endTime = Date.now();
    var diffTime = this.endTime - this.startTime;
    console.log("load time:"+diffTime);
  }
}

/*
 *  自动检测获取自定义form中的数据，收集input,select,textarea元素的数据
 *  为元素添加以下属性可以自定义字段属性
 *  necessary 必须字段，检测非空
 *  errmsg 必须字段为空时提示信息
 *  ignore 忽略字段，不收集
 *  format 特殊格式字段，目前有mobile、qq、age，检测格式
 *  formatErrMsg 特殊格式字段检测不通过时提示信息
 *  return 
 *  {errcode: 0, errmsg: "", data: {}}
 *  检测不通过时 errcode 不为0 ,errmsg为错误信息
 *  data内非ignore字段的值
*/
$.fn.checkForm = function(){
  if(this.length == 0){
    return null;
  }

  var result = {errcode: 0, errmsg: "", data: {}};
  var obj = this.eq(0);
  
  var el = obj.find("input, select, textarea");
  el.each(function(){
    if( this.getAttribute("ignore") !== null ) return;  //忽略
    //console.log(this.getAttribute("necessary"))

    switch(this.type){
      case "checkbox":
        result.data[this.name] = this.checked;
        break;
      default:
        result.data[this.name] = this.value;
        break;
    }
    if(this.getAttribute("necessary") !== null){
      if( $.trim(this.value) === "" && result.errcode == 0){
        result.errcode = -1;
        var errmsg = this.getAttribute("errmsg");
        result.errmsg = errmsg ? errmsg : "请填写完整信息再提交~";        
      }
    }

    var format = this.getAttribute("format");
    if( format !== null ){
      var flag = true;
      switch(format.toLowerCase()){
        case "mobile":
          if( !result.data[this.name].match(/^1(3|4|5|7|8)[0-9]{9}$/) && result.errcode == 0){
            result.errcode = -2;
            var formatErrMsg = this.getAttribute("formatErrMsg");
            result.errmsg = formatErrMsg ? formatErrMsg : "手机号码格式有误~";
            return;
          }
          break;
        case "qq":
          if( isNaN( result.data[this.name] ) && result.errcode == 0 ){
            result.errcode = -2;
            var formatErrMsg = this.getAttribute("formatErrMsg");
            result.errmsg = formatErrMsg ? formatErrMsg : "QQ号码格式有误~";
          }
          break;
        case "age":
          if( (isNaN( result.data[this.name] ) || Number(result.data[this.name]) < 1) && result.errcode == 0 ){
            result.errcode = -2;
            var formatErrMsg = this.getAttribute("formatErrMsg");
            result.errmsg = formatErrMsg ? formatErrMsg : "请填写正确的年龄~";
          }
          break;
        default:
          break;
      }
    }
  })
  if(result.errcode != 0){
    console.log(result);
  }
  return result;
}


var musicControl = {
  supportAudio: false,
  myVideo: null,
  init: function(){
    musicControl.supportAudio = (document.createElement('audio').canPlayType);
    if(!musicControl.supportAudio){
      console.log("该浏览器上暂不支持播放");
      return;
    }else{
      musicControl.myVideo = document.querySelectorAll('audio').length>0?document.querySelectorAll('audio')[0]:null;
      if(musicControl.myVideo == null ) {return;}
    }

    //开启、关闭声音
    $(".icon_audio").on(eventName.tap,function(e){
      if (musicControl.myVideo.paused){
          musicControl.myVideo.play()
          $('.icon_audio').addClass('on');
      }
      else{
          musicControl.myVideo.pause()
          $('.icon_audio').removeClass('on');
      }
    })

    //个别苹果设备交互触发
    $(window).on(eventName.tap,function(){
      if ( musicControl.myVideo.paused && $('.icon_audio').hasClass('on')){
        musicControl.myVideo.play();
      }
    })
  }
}

function getPageApi(url,postData,callback,param){
  if(typeof sending == "undefined"){
    window.sending = false;
  }
  if(!sending){
    $.ajax({
      url: url,
      type: param && param.type == "get" ? "get" : "post",
      dataType: param && typeof param.dataType != "undefined" ? dataType : 'json',
      data: postData,
      beforeSend: function() {
        sending = true;
      },
      error: function() {
        sending = false;
        callback({errcode: -404, errmsg: "服务器忙...请稍候再试~"});
      },
      success: function(data) {
        sending = false;
        console.log(data);
        callback(data);
      }
    });
  }else{
    console.log("正在通信中...");
  }
}

//判断终端类型
function IsPC()  {  
   var userAgentInfo = navigator.userAgent;  
   var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod", "Nokia");  
   var flag = true;  
   for (var v = 0; v < Agents.length; v++) {  
       if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }  
   }  
   return flag;  
}

//随机数
function getRandom(a , b , toFixNum){
  if(a>b){
    a= [b, b=a][0];
  }
  if(!toFixNum){
    var rand = Math.round(Math.random()*(b-a)*1000);
    return rand % (b-a+1) + a;
  }else{
    var n = Math.random()*(b-a)+a;
    return Number(n.toFixed(toFixNum));
  }
}

function getWinSize(){
  var winWidth = 0 , winHeight = 0;
  // 获取窗口宽度
  if (window.innerWidth)
  winWidth = window.innerWidth;
  else if ((document.body) && (document.body.clientWidth))
  winWidth = document.body.clientWidth;
  // 获取窗口高度
  if (window.innerHeight)
  winHeight = window.innerHeight;
  else if ((document.body) && (document.body.clientHeight))
  winHeight = document.body.clientHeight;
  // 通过深入 Document 内部对 body 进行检测，获取窗口大小
  if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth)
  {
  winHeight = document.documentElement.clientHeight;
  winWidth = document.documentElement.clientWidth;
  }
  return {width:winWidth,height:winHeight};
}

//获取url参数
function GetQueryString(name){
  var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
  //解析中文
  var link = decodeURI(window.location.search);
  var r = link.substr(1).match(reg);
  if(r!=null)return  unescape(r[2]); return null;
}

var utils = {
  addEvent: function(el, type, fn, capture){
    el.addEventListener(type, fn, !!capture);
  },
  removeEvent: function(el, type, fn, capture){
    el.removeEventListener(type, fn, !!capture);
  }
},
support = (window.Modernizr && Modernizr.touch === true) || (function () {
  return !!( ('ontouchstart' in window)  || window.DocumentTouch && document instanceof DocumentTouch);
})(),
eventName = {
  start: support ? 'touchstart' : 'mousedown',
  move: support ? 'touchmove' : 'mousemove',
  end: support ? 'touchend' : 'mouseup',
  tap: support ? 'click' : 'click'
};


var viewControl = (function(){
  var maskCount = 0;

  return {
    alert:function(msg,type){
      var msgObj = $(".alert_layer");
      msgObj.find(".msg").html(msg);

      viewControl.layerShow(msgObj,"fade");
    },
    layerShow: function(obj, type, isFullMask){
      if(typeof isFullMask == "undefined" || isFullMask == true){
        $(".fullmask").removeClass("none").animate({"opacity":"1"},400);
        maskCount ++ ;
      }
      var inType = "fadeInDown";
      var outType = "fadeOutUp";
      switch(type){
        case "bounce":
          inType = "bounceIn";
          outType = "bounceOut";
          break;
        default:
          inType = "fadeInDown";
          outType = "fadeOutUp";
          break;
      }

      obj.removeClass("none");
      obj.addClass(inType+" animated");
      obj.find(".close,.comfirm").off().on(eventName.tap,function(){
        viewControl.layerHide($(this).parents(".bounceBox"), inType, outType);
      })
    },
    layerHide: function(obj, inType, outType){
      if(!inType) inType = "fadeInDown";
      if(!outType) outType = "fadeOutUp";
      maskCount = maskCount>0?maskCount-1:0;
      obj.removeClass(inType).addClass(outType);
      setTimeout(
        (function(_obj){
          return function(){
            _obj.addClass("none");
            _obj.removeClass(outType + " animated");
            if(maskCount <= 0){
              $(".fullmask").animate({"opacity":"0"},100,function(){
                $(this).addClass("none");
              })
            }
          }
        }(obj))
      ,500);
    },
    contectLoading: function(act){
      if(act == "show"){
        $('.connenting').removeClass("none");
      }else{
        $(".connenting").addClass("none");
      }
    },
    showMsg: function(msg,duration){
      duration = typeof duration == 'number'? duration:2000;
      var rid = "showMsg"+getRandom(1,10000000);
      var showMsg = $("<div class='showMsg' id='"+rid+"'></div>");
      showMsg.append("<div class='content'>"+msg+"</div>")
      $("body").append(showMsg.animate({"opacity": 1},100));

      setTimeout((function(_showMsg){
        return function(){
          _showMsg.animate({"opacity": 0},400,function(){
            $(this).remove();
          })
        }
      }(showMsg)),duration)
    }
  }
}()) 

//自定义简易滑动类
function Swipe(selector, option){
  this.el = typeof selector == "string" ? document.querySelector(selector) : selector[0];

  this.direction = option.direction=="vertical" ? "vertical" : 'horizontal';
  this.threshold = option.threshold ? option.threshold : 80;
  this.data = {};
  this.onSwipeNext = option.onSwipeNext ? option.onSwipeNext : null;
  this.onSwipePrev = option.onSwipePrev ? option.onSwipePrev : null;
  this.onSwipeMove = option.onSwipeMove ? option.onSwipeMove : null;
  this.addSwipeEnent();
}
Swipe.prototype = {
  addSwipeEnent:function(){
    utils.addEvent(this.el, eventName.start, this);
  },
  handleEvent: function(e){
    switch(e.type){
        case 'touchstart':
        case 'mousedown':
          this._start(e);
          break;
        case 'touchmove':
        case 'mousemove':
          this._move(e);
          break;
        case 'touchend':
        case 'mouseup':
          this._end(e);
          break;
      }
  },
  _start:function(e){
    var touches = support ? e.touches[0] : e;
  
    this.data = {
      startX: touches.pageX,
      startY: touches.pageY,
      distX: 0, // 移动距离
      distY: 0,
      time: +new Date
    }
    
    //绑定this后默认调用this.handleEvent
    utils.addEvent(this.el, eventName.move, this);
    utils.addEvent(this.el, eventName.end, this);
  },
  _move: function(e){
    var touches = support ? e.touches[0] : e;

    this.data.distX = touches.pageX - this.data.startX;
    this.data.distY = touches.pageY - this.data.startY;
    if(this.onSwipeMove){
      this.onSwipeMove({x:this.data.distX,y:this.data.distY});
    }
    e.preventDefault();
  },
  _end: function(e){
    this._triggerEvent();
    utils.removeEvent(this.el, eventName.move, this);
    utils.removeEvent(this.el, eventName.end, this);
  },
  _triggerEvent: function(){
    var _this = this;
      duration = +new Date - this.data.time
      //触发事件
      if(this.data.distX < -this.threshold){
        _this.swipeLeft();
      }else if(this.data.distX > this.threshold){
        _this.swipeRight();
      }
      if(this.data.distY < -this.threshold){
        _this.swipeUp();
      }else if(this.data.distY > this.threshold){
        _this.swipeDown();
      }
  },
  //dir:0为横向1为竖向
  rollback: function(dir){
  },
  swipeUp:function(){
    this.onSwipeNext && this.direction == "vertical" ? this.onSwipeNext() : null;
  },
  swipeDown:function(){
    this.onSwipePrev && this.direction == "vertical" ? this.onSwipePrev() : null;
  },
  swipeLeft:function(){
    this.onSwipeNext && this.direction == "horizontal" ? this.onSwipeNext() : null;
  },
  swipeRight:function(){
    this.onSwipePrev && this.direction == "horizontal" ? this.onSwipePrev() : null;
  }
}

function addShareJs(){
  var nBody = document.body || document.getElementsByTagName('body')[0];
  var shareJS = [
        "http://res.wx.qq.com/open/js/jweixin-1.0.0.js",
        "http://wx.vivo.com.cn/api/js/weixin.js?list=onMenuShareTimeline,onMenuShareQQ,onMenuShareAppMessage,onMenuShareWeibo,scanQRCode",
        basePath + "js/weixin.js" + (typeof version != "undefined"?version:"")
    ];

    for( var i in shareJS){
      if(typeof shareJS[i] != "function"){
        
        var node = document.createElement('script');

        node.charset = 'utf-8';
        node.async = false;
        node.src = shareJS[i];

        nBody.appendChild(node);
      }
    }
}

//判断是否存在于数组中
function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}