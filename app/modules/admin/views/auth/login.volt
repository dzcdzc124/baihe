<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="language" content="en" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>管理后台</title>
    <link rel="stylesheet" type="text/css" href="{{ static_url('components/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('components/iCheck/square/blue.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('components/toastr/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('components/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('css/main.css') }}">

    <script src="{{ static_url('components/jquery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ static_url('components/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ static_url('components/underscore/underscore.min.js') }}"></script>
    <script src="{{ static_url('components/iCheck/icheck.min.js') }}"></script>
    <script src="{{ static_url('components/toastr/toastr.min.js') }}"></script>
    <script src="{{ static_url('components/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ static_url('components/jquery-form/jquery.form.min.js') }}"></script>
    <script src="{{ static_url('components/validator/jquery.validator.js') }}"></script>
    <script src="{{ static_url('components/validator/local/zh_CN.js') }}"></script>
    <script src="{{ static_url('js/app.min.js') }}"></script>
    <script src="{{ static_url('js/main.min.js') }}"></script>

</head>
<body class="login-page">
    <div class="login-box">
        <div class="login-box-body">
            <p class="login-box-msg">请登录后使用...</p>
            <form action="{{ request.getURI() }}" method="post" target="hiddenwin" id="login-form">
                <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
                <div class="form-group has-feedback">
                    {{ form.render('username', ['class': 'form-control mono', 'placeholder': 'Username', 'tabindex': 1]) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    {{ form.render('password', ['class': 'form-control mono', 'placeholder': 'Password', 'tabindex': 2]) }}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck" style="margin-top:6px;margin-bottom:6px;">
                            <label>
                                {{ form.render('rememberMe', ['checked': 'checked']) }} 记住登录
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">登&nbsp;&nbsp;录</button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-success btn-flat" data-remote="{{ url('/admin/auth/register/') }}" data-trigger="dialog"><i class="fa fa-user-plus"></i> 创建一个新的管理员</a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });

        $('#login-form').submit(function(e){
            e.preventDefault();

            if (!$('#username').val()||!$('#password').val()) {
                toastr.error('请输入你的用户名和密码！');
                return;
            }

            var $form = $(this);
            if (!$form.data('submitting')) {
                $form.data('submitting', true);
                var url = $form.attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: $form.serialize(),
                    beforeSend: function(){
                        $form.data('submitting', true);
                    },
                    error: function(){
                        $form.removeData('submitting');
                    },
                    success: function(data){
                        $form.removeData('submitting');
                        if (data.errcode == 0) {
                            toastr.success(data.errmsg);
                            setTimeout(function(){
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error(data.errmsg);
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
</html>