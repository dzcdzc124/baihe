//接口地址
var apiUrl = {
  submit:      baseLink + "api/index/submit",
  order:       baseLink + "api/index/order",
  result:      baseLink + "api/index/result",
  exchange:    baseLink + "api/index/exchange",
  info:        baseLink + "api/index/info",
  getOrder:    baseLink + "api/index/getOrder"
}


var sending = false;

var img_list = [
    basePath + "img/arrow.png",
    basePath + "img/back-test.png",
    basePath + "img/bg.jpg",
    basePath + "img/bg2.jpg",
    basePath + "img/btn.png",
    basePath + "img/icon.png",
    basePath + "img/image1.png",
    basePath + "img/image2.png",
    basePath + "img/loading.jpg",
    basePath + "img/music_on.png",
    basePath + "img/music_off.png",
    basePath + "img/prev.png",
    basePath + "img/qrcode2.png",
    basePath + "img/shape1.png",
    basePath + "img/shape2.png",
    basePath + "img/share.png",
    basePath + "img/start.png",
    basePath + "img/user-center.png"
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
          $(".main .icon").removeClass('rotateY infinite');
          $('.pageloading .user-center, .pageloading .start').removeClass('none').animate({
            'opacity': 1
          }, 400);
          pageControl.loadComplete();
          /*$(".pageloading").animate({
            opacity: 0
          }, 1000, function(){
            $(".pageloading").remove();
          })*/
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
      $('.start').on(eventName.tap, function(){
        $('.pageloading').animate({
          'opacity': 0
        },200, function(){
          $(this).addClass('none');
          $('.questions').removeClass('none').animate({
            'opacity': 1
          }, 200)
        })
      })

      //上一题
      $('.prev').on(eventName.tap, function(){
        if( selecting ) return;
        selecting = true;

        if(questionNo > total_question){
          questionNo --;
          $(".submit").animate({
            'opacity': 0
          }, 200, function(){
            $(this).addClass("none");
            $(".questionList").removeClass('none').animate({
              'opacity': 1
            }, 200, function(){
              selecting = false;
            })
          })

        }else if(questionNo > 0){
          $(".question-"+questionNo).animate({
            "opacity": 0
          }, 200, function(){
            $(this).addClass("none");
            questionNo--;
            $(".count span").html(questionNo);
            $(".question-"+questionNo).removeClass('none').animate({
              'opacity': 1
            }, 200, function(){
              selecting = false;
            })
            console.log(questionNo);
            if( questionNo == 0 && !$('.prev').hasClass('none') ){
              $('.prev').animate({
                'opacity': 0
              }, 200, function(){
                $(this).addClass("none");
              })
            }
          })
        }
      })

      //答题
      $(".questionBox .answer").on(eventName.tap, function(){
        if( selecting ) return;
        selecting = true;

        $(this).addClass("selected").siblings().removeClass("selected");
        var score = $(this).attr("data-value");
        $(this).parents(".questionBox").find('input[type=hidden]').val(score);
        console.log(pageControl.getResult());

        pageControl.iconRotate();

        if(questionNo < total_question){
          setTimeout(function(){
            $(".question-"+questionNo).animate({
              "opacity": 0
            }, 100, function(){
              $(this).addClass("none");
              questionNo++;
              $(".count span").html(questionNo);
              $(".question-"+questionNo).removeClass('none').animate({
                'opacity': 1
              }, 100, function(){
                selecting = false;
              })

              if(questionNo > 0 && $('.prev').hasClass('none')){
                $('.prev').removeClass('none').animate({
                  'opacity': 1
                }, 200)
              }
            })
          }, 400)

        }else{
          questionNo++;
          setTimeout(function(){
            $(".questionList").animate({
              'opacity': 0
            }, 200, function(){
              $(this).addClass("none");
              $(".submit").removeClass('none').animate({
                'opacity': 1
              }, 200, function(){
                selecting = false;
              })
            })
          }, 500)
        }
      })

     //提交结果
      $(".submit").on(eventName.tap, function(){
        if( !$('.prev').hasClass('none') ){
          $('.prev').animate({
            'opacity': 0
          }, 200, function(){
            $(this).addClass("none");
          })
        }

        var result = pageControl.getResult();
        //result = ["1", "1", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "2", "6", "6", "6", "6", "6"];
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
        getPageApi( apiUrl.submit, {'result': JSON.stringify(result), 'sex': sex}, pageControl.submitCallback);
      })

      //支付
      $(".preview .pay").on(eventName.tap, function(){
        var order_id = $(".preview input[name=order_id]").val();
        if(order_id){
          $(".connenting").removeClass("none");
          getPageApi( apiUrl.order, {"order_id": order_id}, pageControl.orderCallback);
        }else{
          viewControl.showMsg('无效的测试号~');
        }
      })

      //兑换码
      $(".preview .code").on(eventName.tap, function(){
        viewControl.layerShow($(".exchange-layer"));
      })
      //兑换
      $(".exchange-layer .btn-submit").on(eventName.tap, function(){
        var result = $('.exchange-form').checkForm();
        if(result.errcode != 0){
          viewControl.showMsg(result.errmsg);
          return;
        }else{
          var postData = result.data;
          var order_id = $(".preview input[name=order_id]").val();
          if(order_id){
            postData["order_id"] = order_id;
            $(".connenting").removeClass("none");
            getPageApi( apiUrl.exchange, postData, pageControl.exchangeCallback);
          }else{
            viewControl.showMsg('无效的测试号~');
          }
        }
      })

      //会员中心
      $('.user-center').on(eventName.tap, function(){
        $(".connenting").removeClass("none");
        getPageApi( apiUrl.info, {}, pageControl.infoCallback);


        $(this).parents('.part').animate({
          'opacity': 0,
        }, 300, function(){
          $(this).addClass('none');
          $('.userinfo').removeClass('none').animate({
            'opacity': 1
          }, 300)
        })
      })

      //返回测试
      $('.back-test').on(eventName.tap, function(){
        questionNo = 0;
        $(".questionBox").find('input[type=hidden]').val(0);
        $(".question-"+questionNo).removeClass('none').css({'opacity': 1}).siblings().addClass("none opacity-0");

        $(".submit").addClass("none opacity-0");
        $(".questionList").removeClass('none').css({'opacity': 1});
        $(".questionList .answer").removeClass('selected');

        $(".userinfo").animate({
          'opacity': 0,
        }, 300, function(){
          $(this).addClass('none');
          $('.questions').removeClass('none').animate({
            'opacity': 1
          }, 300)
        })
      })

      //联系我们
      $('.result .connect').on(eventName.tap, function(){
        viewControl.layerShow($('.qrcode-layer'));
      })
      //分享
      $('.result .share').on(eventName.tap, function(){
        viewControl.layerShow($('.share-layer'));
      })
    },
    iconRotate: function(){
      if( !$('.main .icon').hasClass('rotateY') ){
        $('.main .icon').addClass('rotateY');
        setTimeout(function(){
          $('.main .icon').removeClass('rotateY');
        }, 1000)
      }
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
    submitCallback: function(data){
      $(".connenting").addClass("none");
      if(data.errcode == 0){
        $(".previewBox .result-tle span").html(data.type);
        $(".preview input[name=order_id]").val(data.order_id);

        $(".questions").animate({
          'opacity': 0,
        }, 300, function(){
          $(this).addClass('none');
          $('.preview').removeClass('none').animate({
            'opacity': 1
          }, 300)
        })
      }else{
        viewControl.showMsg(data.errmsg);
      }
    },
    resultCallback: function(data){
      $(".connenting").addClass("none");
      if(data.errcode == 0){
        $(".resultBox .result-tle span").html(data.type);
        var descHtml = '';
        for(var i in data.desc){
          descHtml += '<dt>'+data.desc[i].title+'</dt>';
          descHtml += '<dd>'+data.desc[i].intro+'</dd>';
        }
        $(".resultBox .desc dl").html(descHtml);

        pageControl.iconRotate();

        var selector= '';
        if( !$('.preview').hasClass('none') ){
          selector = '.preview';
        }else{
          selector = '.userinfo';
        }

        $(selector).animate({
          'opacity': 0,
        }, 300, function(){
          $(this).addClass('none');
          $('.result').removeClass('none').animate({
            'opacity': 1
          }, 300)
        })
      }else{
        viewControl.showMsg(data.errmsg);
      }
    },
    orderCallback: function(data){
      $(".connenting").addClass("none");
      if(data.errcode == 0){
        if( typeof data['appId'] != "undefined" ){
            if(debug){
              pageControl.wxPayCallback({errMsg: "chooseWXPay:ok"});
              return;
            }else{
              window.chooseWXPay(data, pageControl.wxPayCallback);
            }
        }else{
          viewControl.showMsg('已完成支付~');
          pageControl.resultCallback(data);
        }
      }else{ 
        viewControl.showMsg(data.errmsg);
      }
    },
    infoCallback: function(data){
      $(".connenting").addClass("none");
      if(data.errcode == 0){
        $('.userinfo .order-list li').not('.temp').remove();

        if(data.list.length > 0){
          for(var i in data.list){
            var order = data.list[i];
            var item = $('.userinfo .order-list li.temp').clone();
            item.find('.result-tle').html(order['data']);
            item.find('.time').html(order['created']);
            if(order['type']){
              item.addClass(order['type']);
            }
            item.attr('order_id', order['order_id']);
            $('.userinfo .order-list ul').append(item.removeClass('temp none'));
          }
        }else{
          $('.userinfo .order-list ul').append('<li class="tips">还没有测试记录<br>赶快去测试吧~</li>');
        }

        $('.userinfo .order-list li').off().on(eventName.tap, function(){
          var order_id = $(this).attr('order_id');
          if(!order_id){
            viewControl.showMsg('无效测试~');
            return;
          }

          $(".connenting").removeClass("none");
          getPageApi(apiUrl.getOrder, {"order_id": order_id}, function(data){
            $(".connenting").addClass("none");
            if(data.errcode == 0){
              pageControl.resultCallback(data);
            }else if(data.errcode == 1){
              //未支付或兑换
              viewControl.showMsg(data.errmsg);
              $(".previewBox .result-tle span").html(data.type);
              $(".preview input[name=order_id]").val(data.order_id);

              $('.userinfo').animate({
                'opacity': 0,
              }, 300, function(){
                $(this).addClass('none');
                $('.preview').removeClass('none').animate({
                  'opacity': 1
                }, 300)
              })
            }else{
              viewControl.showMsg(data.errmsg);
            }
          })
        })
      }else{  
        viewControl.showMsg(data.errmsg);
      }
    },
    wxPayCallback: function(res){
      console.log(res);
      switch(res.errMsg){
        case "chooseWXPay:ok":
          var order_id = $(".preview input[name=order_id]").val();
          $(".connenting").removeClass("none");
          getPageApi( apiUrl.result, {'order_id': order_id}, pageControl.resultCallback);
          break;
        case "chooseWXPay:fail":
          viewControl.showMsg("支付失败");
          break;
        case "chooseWXPay:cancel":
        default:
          break;
      }
    },
    exchangeCallback: function(data){
      $(".connenting").addClass("none");
      if(data.errcode == 0){
        viewControl.showMsg("兑换成功");
        viewControl.layerHide($(".exchange-layer"));
        pageControl.resultCallback(data);
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
  musicControl.init();
})
