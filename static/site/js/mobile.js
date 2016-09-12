//接口地址
var apiUrl = {
  check:      baseLink + "api/imei/check/",
  info:       baseLink + "api/imei/info/",
  award:      baseLink + "api/award/",
  exchange:   baseLink + "api/exchange/",
}


//本地保存已获取城市数据
var districtData = {};
var sending = false;

var loadimg = [
  basePath + "img/back.png",
  basePath + "img/cover.png",
  basePath + "img/shade.png",
];
var img_list = [
    basePath + "img/bg.jpg",
    basePath + "img/logo.jpg",
    basePath + "img/gift.png",
    basePath + "img/redpackage.png",
    basePath + "img/btn.png",
    basePath + "img/alert.png",
    basePath + "img/rule1.png",
];

var imgs = document.images;
for(var i = 0; i<imgs.length; i++){
  var src = imgs[i].src || imgs[i].getAttribute("pre");
  if( src && !in_array(src, img_list) ){
    img_list.push(src);
  }
}

var autoCity = true;

var pageControl = (function () {
  var loadObj = null,
      swiperObj = null,
      winnerswiper = null,
      codeTime = 90,        //验证码有效时间
      countDownTimer = null,//验证码倒计时计时器
      winwidth = 640,       //页面宽
      winheight = 960,      //页面高
      designWidth = 640,    //设计宽
      designHeight = 1138,  //设计高
      slideWidth = 640,     //滑动宽
      slideHeight = 960,    //滑动高
      winscale = 1,         //页面缩放至全部预览
      wincover = 1,         //页面放大至充满屏幕
      direction = "horizontal";  //页面切换方向，horizontal or vertical

  return {
    init: function(){
      //预加载
      new preLoad({
        file_list: loadimg,
        callback: function(){
          $(".pageloading .loader").removeClass("none");

            loadObj = new preLoad({
              file_list: img_list,
              process: function(progress){
                $(".loader .process span").html(progress);
              },
              callback: function(){
                var imgs = document.images;
                for(var i = 0; i<imgs.length; i++){
                  if(!imgs[i].src) {
                    imgs[i].src = imgs[i].getAttribute("pre");
                  }
                }

                $(".part1").addClass("active");

                $(".pageloading").animate({
                  opacity: 0
                }, 200, function(){
                  $(".pageloading").remove();
                  pageControl.loadComplete();
                })
              }
            })

        }
      })

      var winsize = getWinSize();
      winwidth = winsize.width;
      winheight = winsize.height;

      if(winwidth/winheight > designWidth/designHeight){
        winscale = winheight / designHeight;
        wincover = winwidth / designWidth;
      }else{
        winscale = winwidth / designWidth;
        wincover = winheight / designHeight;
      }

      if(IsPC()){
        $("body,.pageloading").width(designWidth).height(designHeight).css({
          '-webkit-transform-origin':'top center',
                  'transform-origin':'top center',
          '-webkit-transform': "scale("+winscale+")",
                  'transform': "scale("+winscale+")",
          'position': 'relative',
          'margin': '0 auto'
        });
        $(".swiper-slide").width(designWidth).height(designHeight);

        
      }else{
        if(winwidth != designWidth){
          var wh = winheight * designWidth/ winwidth;
          $("body").width(designWidth).height(wh).css({
            '-webkit-transform-origin':'top left',
                    'transform-origin':'top left',
            '-webkit-transform': "scale("+ (winwidth/designWidth) +")",
                    'transform': "scale("+ (winwidth/designWidth) +")",
          })
          $(".swiper-container, .swiper-slide").width(designWidth).height(winheight * designWidth/ winwidth);
          winscale = winscale / (winwidth/designWidth);
          wincover = wincover / (winwidth/designWidth);

        }else{
          $("body, .swiper-container, .swiper-slide").width(winwidth).height(winheight);
        }

        if($(".winscale").length > 0){
          $(".winscale").css({
            '-webkit-transform': "scale("+winscale+")",
                    'transform': "scale("+winscale+")"
          });
        }
        if($(".wincover").length > 0){
          $(".wincover").css({
            '-webkit-transform': "scale("+wincover+")",
                    'transform': "scale("+wincover+")"
          });
        }
      }

    },
    loadComplete: function(){      
      $(".refresh").on(eventName.tap, function(){ window.location.reload();})

      var p = GetQueryString("p");
      if(p){
        p = Number(p);
        p = p > 0? p : 1;
        $(".page"+p).removeClass("none").siblings(".page").addClass("none");
      }

      pageControl.handleEvent();
 
      if(ISWEIXIN && !debug){
        addShareJs();
      }

      if( new Date().getTime() > new Date("2016-08-15 23:59:59").getTime()){
        viewControl.alert("~活动已结束~<br>我们下次见");
      }
      //viewControl.layerShow($(".winner_layer"));
      //viewControl.layerShow($(".reserve_layer"));
      //viewControl.alert("<p>兑奖成功</p>奖品将在20个工作日内寄出<br>请耐心等待");
    },
    handleEvent: function(){
      //规则
      $('.part1 .rule').on(eventName.tap, function(){
        viewControl.layerShow($(".rule_layer"));
      })

      //扫描imei
      $(".page1 .scan").on(eventName.tap, function(){
        if(!debug){
          scanQRCode(function(result){
            var arr = result.split(",");
            if( arr[0].toLowerCase() != "code_128"){
              pageControl.statSave("scan","wrong_imei");
              viewControl.alert("你扫描的不是IMEI码~<br>请扫描正确的IMEI码");
              return;
            }
            
            userData.imei = arr[1];
            $(".connenting").removeClass("none");
            pageControl.statSave("submit","imei");
            getPageApi(apiUrl.check,{"imei": userData.imei}, pageControl.checkCallback);
          })
        }else{
          var result = "CODE_128,866282029999511";
          userData.imeiScan = result;
          var arr = result.split(",");
          if( arr[0].toLowerCase() != "code_128"){
            viewControl.alert("你扫描的不是IMEI码~<br>请扫描正确的IMEI码");
            return;
          }
          $(".page2 .imei").html(arr[1]);
          $(".part2").removeClass("none").siblings().addClass("none");
        }
      })

      //提交抽奖
      $(".page2 .submit").on(eventName.tap, function(){
        var result = $(".page2 .form").checkForm();
        if(result.errcode == -1){
          viewControl.alert("您的信息填写不完整<br>请补充");
          return;
        }else if(result.errcode != 0){
          viewControl.alert(result.errmsg);
          return;
        }

        var postData = result.data;
        for(var item in postData){
          userData[item] = postData[item];
        }
        console.log(postData);
        
        if(!debug){
          $(".connenting").removeClass("none");
          pageControl.statSave("submit","info");
          getPageApi( apiUrl.info, postData, pageControl.infoCallback);
        }else{
          pageControl.awardCallback({errcode: 0, prizeType: getRandom(1,1) ,redpacket:{amount: 105}, gift:{data1: "宾果消消乐", data2: "JEJV13532523"} });
        }
      })

      //提交中奖信息
      $(".page3 .x7 .confirm").on(eventName.tap, function(){
        var result = $(".page3 .x7 .box").checkForm();
        if(result.errcode == -1){
          viewControl.alert("<p>再坚持1秒</p>请您填写完整信息再提交");
          return;
        }else if(result.errcode != 0){
          viewControl.alert(result.errmsg);
          return;
        }

        var postData = result.data;
        for(var item in postData){
          userData[item] = postData[item];
        }
        console.log(postData);
        
        if(!debug){
          $(".connenting").removeClass("none");
          pageControl.statSave("submit","exchange");
          getPageApi( apiUrl.exchange, postData, pageControl.exchangeCallback);
        }else{
          pageControl.exchangeCallback({errcode: 0});
        }
      })
    },
    checkCallback: function(data){
      $(".connenting").addClass("none");
      if(data.errcode == 0){
        if(typeof data.lucky == "undefined"){
          $(".page2 .imei").html(userData.imei);
          $(".part2").removeClass("none").siblings().addClass("none");
        }else{
          var type = Number(data.lucky.prizeType);
          if( $.inArray(type,[1,2,3]) >=0 ){
            $(".part3").removeClass("none").siblings().addClass("none");
            pageControl.setResult(data.lucky);
          }else{
            viewControl.alert("<p>很遗憾</p>你没有中奖，感谢参与!");
          }
        }
      }else{
        viewControl.alert(data.errmsg);
      }
    },
    infoCallback: function(data){
      if(data.errcode == 0){
        pageControl.statSave("submit","award");
        getPageApi( apiUrl.award, {}, pageControl.awardCallback);
      }else{
        $(".connenting").addClass("none");
        viewControl.showMsg(data.errmsg);
      }
    },
    awardCallback: function(data){
      $(".connenting").addClass("none");
      console.log(data);
      if(data.errcode == 0){
        var type = Number(data.prizeType);
        if( $.inArray(type,[1,2,3]) >=0 ){
          $(".part2").animate({
                    "transform": "translate(0, -200%)",
            "-webkit-transform": "translate(0, -200%)",
          }, 400, function(){
            $(".part3").removeClass("none").siblings().addClass("none");
            pageControl.setResult(data);
          })
        }else{
          viewControl.alert("<p>很遗憾</p>你没有中奖，感谢参与!");
        }
      }else{
        viewControl.showMsg(data.errmsg);
      }
    },
    setResult: function(data){
      switch( Number(data.prizeType) ){
        //礼包
        case 3:
          $(".page3 .gift").removeClass("none").siblings().addClass("none");
          $(".page3 .gift .box").html("<p class='fs32'>恭喜您抽中</p><p class='fs32 ellipsis'>《"+ data.gift.data1 +"》</p><p class='fs32'>游戏大礼包!</p><p class='fs32'>兑换码:"+ data.gift.data2 +"</p><br><p class='lh40'>礼包兑换方法已发到您的手机短信</p><p class='lh40'>可以畅玩起来啦!</p>");
          $(".page3 .gift .box").addClass("bounceInDown animated");
  
          break;
        //红包
        case 2:
          var money = data.redpacket.amount/100;
          $(".page3 .redpackage").removeClass("none").siblings().addClass("none");
          $(".page3 .redpackage .box").html("<p class='fs40'>恭喜您获得红包!</p><br><p class='money'>￥<span>"+money+"</span></p><br><br><p>恭喜中奖！</p><p>快到vivo智能手机领取红包吧!</p>");
          $(".page3 .redpackage .box").addClass("bounceInDown animated");
          
          break;
        //x7
        case 1:
          $(".page3 .x7").removeClass("none").siblings().addClass("none");
          $(".page3 .x7 .box .fs36").html("恭喜抽中X7Plus手机一台!");
          $(".page3 .x7 .box").addClass("bounceInDown animated");
          setTimeout(function(){
            $(".page3 .x7 .rainbow, .page3 .x7 .infoBox").animate({
              opacity: 1
            }, 800);
          }, 800);
          setTimeout(function(){
            $(".page3 .x7 .confirm").animate({
              opacity: 1
            }, 800);
          }, 2000);
          break;
        default: 
          break;
      }
    },
    exchangeCallback: function(data){
      $(".connenting").addClass("none");
      if(data.errcode == 0){
        viewControl.alert("<p>兑奖成功</p>奖品将在20个工作日内寄出<br>请耐心等待");
        $(".page3 .x7 .confirm").animate({
          opacity: 0
        }, 300, function(){
          $(this).addClass("none");
        })
      }else{
        viewControl.showMsg(data.errmsg);
      }
    },
    statSave: function(action,type){
      if(typeof _hmt != "undefined"){
          _hmt.push(['_trackEvent', action, action+"_"+type]);
      }
      if(typeof _czc != "undefined"){
          _czc.push(["_trackEvent", action, action+"_"+type]);
      }
    }
  }
}());

$(document).ready(function() {
  pageControl.init();
})
