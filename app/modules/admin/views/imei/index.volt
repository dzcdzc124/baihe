{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/imei']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            IMEI列表
            <small>IMEI</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['IMEI列表']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('partial/nav', [
                        'title': 'IMEI列表',
                        'navs': [
                            ['index', '全部', url('/admin/imei/'), '#ajax-content']
                        ],
                        'current_nav': 'index'
                    ]) }}

                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <table class="table table-hover no-margin">
                                <thead>
                                    <tr>
                                        <th style="width:250px;">IMEI</th>
                                        <th style="width:120px;">区域</th>
                                        <th>OpenID</th>
                                        <th style="width:160px;">姓名</th>
                                        <th style="width:180px;">手机号码</th>
                                        <th class="text-right" style="width:160px;">时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {% for item in page.items %}
                                        <tr>
                                            <td>{{ item.imei }}</td>
                                            <td>{{ item.districtName ? item.districtName : '-' }}
                                            <td>{{ item.openId }}</td>
                                            <td>{{ item.name ? item.name : '-' }}</td>
                                            <td>{{ item.mobile ? item.mobile : '-' }}</td>
                                            <td class="text-right">{{ date('Y-m-d H:i:s', item.created) }}</td>
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
        </div>
        <!-- AJAX_CONTENT_END -->
    </section>
</div>
{% endblock %}