{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            仪表盘
            <small>Dashboard</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['仪表盘']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ userTotal }}</h3>
                        <p>当前用户数</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <a href="{{ url('/admin/user/') }}" class="small-box-footer">查看详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ deleteTotal }}</h3>
                        <p>已删用户数</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-trash"></i>
                    </div>
                    <a href="{{ url('/admin/user/', ['deleted': 1]) }}" class="small-box-footer">查看详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ signupTotal }}</h3>
                        <p>报名数</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ url('/admin/signup/') }}" class="small-box-footer">查看详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>0</h3>
                        <p>记录数</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ url('/admin/record/') }}" class="small-box-footer">查看详情 <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div> -->
        </div>
        <!-- AJAX_CONTENT_END -->
    </section>
</div>
{% endblock %}