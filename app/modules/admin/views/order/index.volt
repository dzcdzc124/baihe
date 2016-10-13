{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/order']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            订单列表
            <small>Order</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['订单列表']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('partial/nav', [
                        'title': '订单列表',
                        'navs': [
                            ['index', '全部订单', url('/admin/order/')],
                            ['payed', '已付订单', url('/admin/order/', ['pay': 1])]
                        ],
                        'current_nav': pay == '1' ? 'payed' : 'index'
                    ]) }}
                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th class="col-md-2">订单号</th>
                                            <th style="width:80px;">金额</th>
                                            <th>是否付款</th>
                                            <th class="text-right">付款时间</th>
                                            <!-- <th class="text-right" style="width:50px;">操作</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in page.items %}
                                        <tr>
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.order_id }}</td>
                                            <td>{{ round( item.total_fee/100, 2) }}</td>
                                            <td>{{ item.status==1 ? '是' : '否' }}</td>
                                            <td class="text-right">{{ item.status==1 ? date('Y-m-d H:i:s', item.updated):'' }}</td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <!-- <a href="#" data-remote="{{ url('/admin/order/index/create/') }}" data-trigger="dialog" class="btn btn-sm btn-default pull-left"><i class="glyphicon glyphicon-plus"></i> 新增奖品</a> -->

                                                <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-floppy-disk"></i> 保存设置</button>
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