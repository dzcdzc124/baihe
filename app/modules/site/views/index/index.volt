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
    var baseLink = "{{ preg_replace('/:\d+/', '', url('/') ) }}";
    var basePath = "<?php echo $basePath; ?>";
    var version = "<?php echo $version; ?>";

    var userData = {
    };
    var total_question = {{count(questionList)}};

    var debug = true;
    <?php if(strpos($_SERVER["HTTP_HOST"],".com")!==false ){ ?>
        debug = false;
    <?php }; ?>

    
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/mobile.css"/>
    <title>百合网·爱情实验室</title>
</head>

<body>
<!-- <div class="refresh"></div> -->
<div class="fullmask none"></div>

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

<div class="exchange-layer bounceBox none">
    <div class="exchage-box page winscale">
        <div class="layer-box">
            <div class="close absolute">X</div>
            <div class="full absolute table">
                <div class="exchange-form table-cell">
                    <div class="relative border-box info-item code-item">
                        <input type="text" name="code" necessary placeholder="请输入兑换码" errmsg="请输入兑换码~">
                    </div>
                </div>
            </div>
        </div>
        <div class="btn btn-submit fs-lg">提交</div>
    </div>
</div>

<div class="qrcode-layer bounceBox none">
    <div class="qrcode-box page winscale">
        <div class="close absolute"></div>
        <img src="<?php echo $basePath; ?>img/qrcode2.png" class="qrcode">
    </div>
</div>

<div class="share-layer bounceBox none">
    <div class="close absolute"></div>
    <img src="<?php echo $basePath; ?>img/share.png" class="share">
</div>

<div class="main">
    <div class="pageloading part">
        <div class="user-center none opacity-0"></div>
        <div class="start none opacity-0"></div>
    </div>

    <div class="page questions part winscale none opacity-0">
        <img src="<?php echo $basePath; ?>img/image1.png" class="poster">
        <div class="questionList">
            <div class="questionBox question-0 none">
                <input type="hidden" name="sex" value="0">
                <div class="sort tc fs32 bold">　</div>
                <div class="question table border-box">
                    <div class="content table-cell va-middle tc">你的性别？</div>
                </div>
                
                <div class="answers">
                    <div class="answer inline-block" data-value="1">男性</div>
                    <div class="answer inline-block" data-value="0">女性</div>
                </div>
            </div>
            {% for item in questionList %}
            <div class="questionBox question-{{item.id}} none">
                <input type="hidden" name="results[{{item.sort}}]" value="0">
                <div class="sort tc fs32 bold">{{ str_pad(item.sort, 2, "0", 0) }}</div>
                <div class="question table border-box">
                    <div class="content table-cell va-middle tc">{{item.question}}</div>
                </div>
                
                <div class="answers">
                    <div class="answer inline-block" data-value="1">A非常不同意</div>
                    <div class="answer inline-block" data-value="2">B不同意</div>
                    <div class="answer inline-block" data-value="3">C有点不同意</div>
                    <div class="answer" style="margin: 20px auto;"  data-value="4">D不确定</div>
                    <div class="answer inline-block" data-value="5">E有点同意</div>
                    <div class="answer inline-block" data-value="6">F同意</div>
                    <div class="answer inline-block" data-value="7">G非常同意</div>
                </div>
            </div>
            {% endfor %}
        </div>
        <div class="submit btn none opacity-0">提　交</div>
        <div class="prev none opacity-0"></div>
        <div class="count">
            <span>0</span>/{{count(questionList)}}
        </div>
    </div>
    <div class="preview part none opacity-0">
        <div class="user-center"></div>
        <img src="<?php echo $basePath; ?>img/image2.png" class="poster winscale">
        <div class="previewBox winscale">
            <div class="result-tle tc fs36">- <span></span> -</div>
            <div class="desc border-box">
                <input type="hidden" name="order_id" value="">
                <div class="tips fs24 tc">- 想了解更多更详细的内容吗？您可以 -</div>
                <div class="pay btn">支付{{ product.total_fee/100 }}元购买详细报告</div>
                <div class="code btn">百合会员兑换码获取</div>
            </div>
        </div>
    </div>
    <div class="result border-box part none opacity-0">
        <div class="page  winscale">
            <div class="result-content">
                <div class="user-center"></div>
                <img src="<?php echo $basePath; ?>img/image2.png" class="poster">
                <div class="resultBox">
                    <div class="result-tle tc fs36">- <span></span> -</div>
                    <div class="desc border-box">
                        <div class="content">
                            <dl>
                               
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="operate tc">
                    <div class="connect btn">联系我们</div>
                    <div class="share btn">分享给朋友</div>
                </div>
            </div>
        </div>
    </div>
    <div class="userinfo part none opacity-0">
        <div class="back-test"></div>
        <div class="page winscale">
            <div class="history border-box">
                <div class="tle tc">历史记录</div>
                <div class="order-list">
                    <ul>
                        <li class="temp tc none">
                            <span class="time"></span>测试：
                            <span class="result-tle"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- <img src="<?php echo $basePath; ?>img/qrcode.png" class="qrcode"> -->
        </div>
    </div>
    <div class="icon rotateY infinite"></div>
</div>


<div class="icon_audio on">
    <div class="earphone"></div>
</div>
<div class="audio-box none">
    <audio src="<?php echo $basePath; ?>music.mp3" autoplay="autoplay" loop="loop"></audio>
</div> 

<script src="<?php echo $basePath; ?>js/zepto.min.js<?php echo $version; ?>"></script>
<script src="<?php echo $basePath; ?>js/common.js<?php echo $version; ?>"></script>

<?php if (strpos($_SERVER["HTTP_HOST"],".com")!==false) { ?>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '{{wxConfig["appId"]}}', // 必填，公众号的唯一标识
            timestamp: {{wxConfig["timestamp"]}}, // 必填，生成签名的时间戳
            nonceStr: '{{wxConfig["nonceStr"]}}', // 必填，生成签名的随机串
            signature: '{{wxConfig["signature"]}}',// 必填，签名，见附录1
            jsApiList: [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'chooseWXPay'
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
    </script>
    <script src="<?php echo $basePath; ?>js/weixin.js<?php echo $version; ?>"></script>
<?php }?>
<script src="<?php echo $basePath; ?>js/mobile.js<?php echo $version; ?>"></script>
</body>
</html>