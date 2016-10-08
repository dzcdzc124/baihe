//接口地址
var apiUrl = {
  result:      baseLink + "index/result",
}


var sending = false;

var img_list = [
    basePath + "img/bg.jpg",
    basePath + "img/icon.png",
    basePath + "img/image1.png",
    basePath + "img/loading.jpg",
    basePath + "img/shape1.png",
    basePath + "img/shape2.png",
];

/*var imgs = document.images;
for(var i = 0; i<imgs.length; i++){
  var src = imgs[i].src;
  if( src && !in_array(src, img_list) ){
    img_list.push(src);
  }
}*/

var questionNo = 0; //当前题号
var selecting = false;

var pageControl = (function () {
  var loadObj = null,
      swiperObj = null,
      winnerswiper = null,
      codeTime = 90,        //验证码有效时间
      countDownTimer = null,//验证码倒计时计时器
      winwidth = 640,       //页面宽
      winheight = 960,      //页面高
      designWidth = 640,    //设计宽
      designHeight = 1050,  //设计高
      slideWidth = 640,     //滑动宽
      slideHeight = 960,    //滑动高
      winscale = 1,         //页面缩放至全部预览
      wincover = 1,         //页面放大至充满屏幕
      direction = "horizontal";  //页面切换方向，horizontal or vertical

  return {
    init: function(){
      //预加载
      loadObj = new preLoad({
        file_list: img_list,
        process: function(progress){
          //$(".loader .process span").html(progress);
        },
        callback: function(){
          $(".pageloading").animate({
            opacity: 0
          }, 1000, function(){
            $(".pageloading").remove();
            pageControl.loadComplete();
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

      $(".question-"+questionNo).removeClass('none').animate({
        "opacity": 1
      }, 500)
 
      pageControl.handleEvent();

      //viewControl.layerShow($(".winner_layer"));
      //viewControl.layerShow($(".reserve_layer"));
      //viewControl.alert("<p>兑奖成功</p>奖品将在20个工作日内寄出<br>请耐心等待");
    },
    handleEvent: function(){
      //答题
      $(".questionBox .answer").on(eventName.tap, function(){
        if( selecting ) return;

        selecting = true;
        $(this).addClass("selected").siblings().removeClass("selected");
        var score = $(this).attr("data-value");
        $(this).parents(".questionBox").find('input[type=hidden]').val(score);
        console.log(pageControl.getResult());

        if(questionNo < total_question){

          setTimeout(function(){
            $(".question-"+questionNo).animate({
              "opacity": 0
            }, 30, function(){
              $(this).addClass("none");
              questionNo++;
              $(".count span").html(questionNo);
              $(".question-"+questionNo).removeClass('none').animate({
                'opacity': 1
              }, 30, function(){
                selecting = false;
              })
            })
          }, 40)

        }else{
          setTimeout(function(){
            $(".questionList").animate({
              'opacity': 0
            }, 300, function(){
              $(this).addClass("none");
              $(".submit").removeClass('none').animate({
                'opacity': 1
              }, 300, function(){
                selecting = false;
              })
            })
          }, 400)
        }
      })

     //提交结果
      $(".submit").on(eventName.tap, function(){
        var result = pageControl.getResult();
        for(var i = 0; i < result.length; i++){
          if(result[i] == 0){
            $(".submit").animate({
              'opacity': 0
            }, function(){
              $(this).addClass("none");

              $(".question-"+ (i+1)).css({'opacity': 1}).removeClass('none').siblings().css({'opacity': 0}).addClass('none');
              $(".questionList").removeClass('none').animate({
                'opacity': 1
              }, 300, function(){
                selecting = false;
                viewControl.showMsg('请重答这一道题~');
              })
            })
            return;
          } 
          result[i] = Number(result[i]);
        }

        console.log(result);
        var sex = $('.question-0').find('input[type=hidden]').val();
        
        $(".connenting").removeClass("none");
        getPageApi( apiUrl.result, {'result': JSON.stringify(result), 'sex': sex}, pageControl.resultCallback);
       
      })
    },
    getResult: function(){
      var result = [];
      $('.questionList .questionBox').each(function(){
        if($(this).hasClass('question-0')){
          return;
        }
        result.push($(this).find('input[type=hidden]').val());
      })
      return result;
    },
    resultCallback: function(){

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
