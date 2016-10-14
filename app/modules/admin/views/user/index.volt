{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/order']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            用户列表
            <small>Order</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['用户列表']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('partial/nav', [
                        'title': '用户列表',
                        'navs': [
                            ['index', '全部用户', url('/admin/order/')]
                        ],
                        'current_nav': 'index'
                    ]) }}
                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th class="col-md-3">openId</th>
                                            <th class="col-md-2">用户昵称</th>
                                            <th class="col-md-1">用户头像</th>
                                            <th>性别</th>
                                            <th>最新测试结果</th>
                                            <th>加入时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in page.items %}
                                        <tr>
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.openId }}</td>
                                            <td>{{ item.nickname }}</td>
                                            <td><img style="width:50px;" src="{{ item.avatar }}"></td>
                                            <td>{{ item.sex == 2 ? '女': (item.sex == 1 ? '男' : '未知') }}</td>
                                            <td>{{ item.result }}</td>
                                            <td>{{ date('Y-m-d H:i:s', item.created) }}</td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <!-- <a href="#" data-remote="{{ url('/admin/order/index/create/') }}" data-trigger="dialog" class="btn btn-sm btn-default pull-left"><i class="glyphicon glyphicon-plus"></i> 新增奖品</a> -->

                                                <!-- <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-floppy-disk"></i> 保存设置</button> -->
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- AJAX_CONTENT_END -->
    </section>
</div>
{% endblock %}