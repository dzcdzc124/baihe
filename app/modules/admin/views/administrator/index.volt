{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'setting/admin']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            后台管理员
            <small>Administrator</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['系统设置', url('/admin/setting/')],
            ['后台管理员']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">后台管理员列表</h3>
                        <div class="box-tools">
                            <a href="#" data-remote="{{ url('/admin/administrator/create/') }}" data-trigger="dialog" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> 新增</a>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-2">用户名</th>
                                    <th class="col-md-1" style="text-align:center;">启用</th>
                                    <th>邮箱</th>
                                    <th>手机号码</th>
                                    <th class="col-md-2">创建时间</th>
                                    <th class="col-md-1 text-right">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                {% for item in page.items %}
                                    <tr>
                                        <td>{{ item.username }}
                                            {% if item.sa %}
                                                <i class="text-danger glyphicon glyphicon-star-empty" title="[超级管理员]"></i>
                                            {% endif %}
                                        </td>
                                        <td style="text-align:center;">{% if item.activated %}<i class="fa fa-check-circle text-green"></i>{% else %}<i class="fa fa-times-circle text-red"></i>{% endif %}</td>
                                        <td>{{ item.email ? item.email : '-' }}</td>
                                        <td>{{ item.mobile ? item.mobile : '-' }}</td>
                                        <td>{{ date('Y-m-d H:i:s', item.created) }}</td>
                                        <td class="text-right">
                                            <a class="btn btn-xs" href="#" data-remote="{{ url('/admin/administrator/update/', ['id': item.id]) }}" data-trigger="dialog"><i class="glyphicon glyphicon-pencil"></i></a>
                                        </td>
                                    </tr>
                                {% endfor %}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <span class="pull-left">共 <span class="text-danger">{{ page.total_items }}</span> 条记录，当前页码：<span class="text-info">{{ page.current }}</span> / {{ max(1, page.total_pages) }}</span>
                                        <ul class="pagination pagination-sm no-margin pull-right" data-toggle="ajax" data-target="#ajax-content">{{ partial('partial/pagination', ['page': page]) }}</ul>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- AJAX_CONTENT_END -->
    </section>
</div>
{% endblock %}