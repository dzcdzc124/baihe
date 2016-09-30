<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">
    
    <?php 
        $isvivo = strpos($_SERVER["HTTP_HOST"],"vivo") !== false;
        $isWeiXin = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "micromessenger") !== FALSE;
        $version = "?v0"; 
        $basePath = App\Helpers\Url::staticUrl('./site/');
        $basePath = substr($basePath , 0 ,strripos($basePath ,"/")+1);
    ?>

    <script type="text/javascript"> 
    var ISMOBILE = {{ isMobile ? 1 : 0 }}, ISWEIXIN = {{ isWeiXin ? 1 : 0 }} ;
    var baseLink = "{{ url('/') }}";
    var basePath = "<?php echo $basePath; ?>";
    var version = "<?php echo $version; ?>";

    var userData = {
        "id": "{{id}}",
        "name": "{{name}}",
        "mobile": "{{mobile}}",
        "city": "{{city}}",
    };

    var debug = true;
    <?php if(strpos($_SERVER["HTTP_HOST"],"vivo")!==false ){ ?>
        debug = false;
    <?php }; ?>

    
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/mobile.css"/>
    <title></title>
</head>

<body>
<div class="refresh"></div>
<div class="fullmask none"></div>


<div class="pageloading">
    <div class="loader wincover none">
        <div class="pic back rotate"></div>
        <div class="pic shade"></div>
        <div class="pic cover"></div>
        <div class="msg">
            <p class="process"><span>0</span> %</p>
        </div>
    </div>
</div>
<div class="connenting none">
    <div class="connentloading">
        <div class="spinner">
          <div class="bounce1"></div>
          <div class="bounce2"></div>
          <div class="bounce3"></div>
        </div>
    </div>
</div>
<div class="alert_layer bounceBox none">
    <div class="alertBox winscale">
        <div class="close absolute"></div>
        <div class="contentBox">
            <div class="table-cell msg">
                <!-- <p>很抱歉</p>
                您的信息填写不完整<br>请补充 -->
            </div>
        </div>
    </div>
</div>
<div class="rule_layer bounceBox none">
    <div class="ruleBox winscale">
        <div class="content">
            <ul>
                <li>
                    <p>1</p>
                    <p>每部X7&X7Plus可凭手机IMEI码获得一次抽奖机会，每个微信账号可参与最多3次不同IMEI码抽奖</p>
                </li>
                <li>
                    <p>2</p>
                    <p>本活动所中实物礼品将在20个工作日内邮寄</p>
                </li>
                <li>
                    <p>3</p>
                    <p>抽中游戏礼包的用户未收到短信，可以再次扫描原IMEI进入中奖页面查看</p>
                </li>
                <li>
                    <p>4</p>
                    <p>抽中手机奖项的用户，需要准确填写地址与身份信息，否则无法兑奖</p>
                </li>
                <li>
                    <p>5</p>
                    <p>本活动法律范围内的解释权归vivo所有</p>
                </li>
                <li>
                    <p>6</p>
                    <p>手机IMEI可以在包装盒背面找到哦</p>
                </li>
            </ul>
        </div>
        <div class="close absolute"></div>
    </div>
</div>

<div class="main">
    <div class="part part1 ">
        <div class="logo"></div>
        <div class="rule">活动详情></div>
        <div class="page1 page winscale ">
            <img pre="<?php echo $basePath; ?>img/tle.png" class="tle">
            <img pre="<?php echo $basePath; ?>img/7.png" class="seven">
            <img pre="<?php echo $basePath; ?>img/tree.png" class="tree">
            <img pre="<?php echo $basePath; ?>img/slogan.png" class="slogan">
            <img pre="<?php echo $basePath; ?>img/man.png" class="man">

            <img pre="<?php echo $basePath; ?>img/piece1.png" class="piece1">
            <img pre="<?php echo $basePath; ?>img/piece2.png" class="piece2">
            <img pre="<?php echo $basePath; ?>img/piece3.png" class="piece3">
            <img pre="<?php echo $basePath; ?>img/piece4.png" class="piece4">

            <img pre="<?php echo $basePath; ?>img/scan.png" class="scan">
        </div>
        <img pre="<?php echo $basePath; ?>img/beach.png" class="beach">
    </div>

    <div class="part part2 none">
        <div class="logo"></div>
        <div class="page page2 winscale">
            <img pre="<?php echo $basePath; ?>img/tle2.png" class="tle2">
            <div class="form">
                <p>您的IMEI串码</p>
                <p class="imei"></p>
                <div class="nameBox infoBox">
                    <div class="item-val">
                        <input type="text" name="name" necessary placeholder="请输入您的姓名">
                    </div>
                </div>
                <div class="mobileBox infoBox">
                    <div class="item-val">
                        <input type="tel" name="mobile" necessary placeholder="请输入您的手机" format="mobile" formatErrMsg="手机号码格式有误<br>请检查">
                    </div>
                </div>
            </div>
            <img pre="<?php echo $basePath; ?>img/submit.png" class="submit">
        </div>
    </div>
    <div class="part part3 none">
        <div class="logo"></div>
        <div class="page page3 winscale">
            <div class="gift none">
                <div class="box">
                    
                </div>
            </div>
            <div class="redpackage none">
                <div class="box">
                    
                </div>
            </div>
            <div class="x7 none">
                <div class="box">
                    <img pre="<?php echo $basePath; ?>img/x7.png" class="phone">
                    <img pre="<?php echo $basePath; ?>img/light.png" class="light">
                    <img pre="<?php echo $basePath; ?>img/rainbow.png" class="rainbow">

                    <p class="fs36"></p>
                    <p>1600万柔光自拍  照亮你的美</p>
                    <div class="nameBox infoBox">
                        <div class="item-val">
                            <input type="text" name="address" necessary placeholder="请输入收货地址">
                        </div>
                    </div>
                    <div class="mobileBox infoBox">
                        <div class="item-val">
                            <input type="tel" name="idcard" necessary placeholder="请输入身份证号">
                        </div>
                    </div>
                </div>
                <img pre="<?php echo $basePath; ?>img/confirm.png" class="confirm">
            </div>
        </div>
    </div>
</div>

<!-- <div class="icon_audio on">
    <div class="earphone"></div>
    <span class="iconaudio1"><s class="icon_audio_anim"></s></span>
    <span class="iconaudio2"><s class="icon_audio_anim"></s></span>
    <span class="iconaudio3"><s class="icon_audio_anim"></s></span>
</div>
<div class="audio-box none">
    <audio src="<?php echo $basePath; ?>img/music.mp3" autoplay="autoplay" loop="loop" ></audio>
</div>  -->

<script src="<?php echo $basePath; ?>js/zepto.min.js<?php echo $version; ?>"></script>
<script src="<?php echo $basePath; ?>js/swiper.min.js<?php echo $version; ?>"></script>
<script src="<?php echo $basePath; ?>js/common.js<?php echo $version; ?>"></script>
<script src="<?php echo $basePath; ?>js/mobile.js<?php echo $version; ?>"></script>
    

<?php if(strpos( strtolower($_SERVER["HTTP_HOST"]) ,"vivo") !== false ) { ?>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?d832622eabb52c1d65721d3ea7100784";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<?php }; ?>

</body>
</html>