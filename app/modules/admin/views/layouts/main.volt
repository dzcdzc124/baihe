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
    <script src="{{ static_url('components/jquery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ static_url('components/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ static_url('components/underscore/underscore.min.js') }}"></script>
    <script src="{{ static_url('components/iCheck/icheck.min.js') }}"></script>
    <script src="{{ static_url('components/toastr/toastr.min.js') }}"></script>
    <script src="{{ static_url('components/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ static_url('components/jquery-form/jquery.form.min.js') }}"></script>
    <script src="{{ static_url('components/validator/jquery.validator.js') }}"></script>
    <script src="{{ static_url('components/validator/local/zh_CN.js') }}"></script>
    {% block assets %}{% endblock %}

    <link rel="stylesheet" type="text/css" href="{{ static_url('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('css/skins/skin-vivo.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ static_url('css/main.min.css') }}">
    <script src="{{ static_url('js/app.min.js') }}"></script>
    <script src="{{ static_url('js/main.min.js') }}"></script>

    {% block header %}{% endblock %}
</head>
<body class="{% block bodyclass %}skin-vivo sidebar-mini fixed{% endblock %}">
    <div class="wrapper">
        {% block content %}{% endblock %}

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <span>Page rendered in {{ elapsed_time() }} second(s), Memory: {{ memory_usage() }}.</span>
            </div>
            <span><strong>Version</strong> 1.0.0</span>
        </footer>
    </div>

    {% block footer %}{% endblock %}
</body>
</html>