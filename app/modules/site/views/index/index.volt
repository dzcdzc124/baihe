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
    };
    var total_question = {{count(questionList)}};

    var debug = true;
    <?php if(strpos($_SERVER["HTTP_HOST"],"lovelab")!==false ){ ?>
        debug = false;
    <?php }; ?>

    
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>css/mobile.css"/>
    <title>PDQ</title>
</head>

<body>
<div class="refresh"></div>
<div class="fullmask none"></div>


<div class="pageloading">
    <div class="loader wincover none">
        
    </div>
    <div class="icon"></div>
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


<div class="main">
    <div class="page questions winscale">
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
                <div class="sort tc fs32 bold">{{ str_pad(item.sort, 2, "0", STR_PAD_LEFT) }}</div>
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
        <div class="count">
            <span>1</span>/{{count(questionList)}}
        </div>
    </div>

    <div class="result none opacity-0">
        <div class="resultBox winscale">
            <div class="tle tc fs36">- <span></span> -</div>
            <div class="desc border-box">
                <dl>
                   
                </dl>
            </div>
        </div>
    </div>
    
    <div class="icon"></div>
</div>


<script src="<?php echo $basePath; ?>js/zepto.min.js<?php echo $version; ?>"></script>
<script src="<?php echo $basePath; ?>js/common.js<?php echo $version; ?>"></script>
<script src="<?php echo $basePath; ?>js/mobile.js<?php echo $version; ?>"></script>

<?php if (strpos($_SERVER["HTTP_HOST"],".com")!==false) { ?>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
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
<?php }?>
</body>
</html>