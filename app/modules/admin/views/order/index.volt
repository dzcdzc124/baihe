{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/order']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            测试列表
            <small>Order</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['测试列表']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('partial/nav', [
                        'title': '测试列表',
                        'navs': [
                            ['index', '全部测试', url('/admin/order/')],
                            ['wxpay', '已付款测试', url('/admin/order/', ['wxpay': 1])],
                            ['code', '已兑换测试', url('/admin/order/', ['code': 1])]
                        ],
                        'current_nav': wxpay == '1' ? 'wxpay' : (code == '1' ? 'code' : 'index')
                    ]) }}
                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th class="col-md-2">测试号</th>
                                            <th style="width:80px;">金额</th>
                                            <th>是否付款/兑换</th>
                                            <th>付款/兑换方式</th>
                                            <th>测试结果</th>
                                            <th class="col-md-2">用户</th>
                                            <th>付款/兑换时间</th>
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
                                            <td>{{ item.type=='wxpay' ? '微信支付' : (item.type=='code' ? '兑换码' : '') }}</td>
                                            <td>{{ item.data }}</td>
                                            <td>{{ item.nickname }}</td>
                                            <td>{{ item.status==1 ? date('Y-m-d H:i:s', item.updated):'' }}</td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <!-- <tr>
                                            <td colspan="8">
                                                <a href="#" data-remote="{{ url('/admin/order/index/create/') }}" data-trigger="dialog" class="btn btn-sm btn-default pull-left"><i class="glyphicon glyphicon-plus"></i> 新增奖品</a>
                                                <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-floppy-disk"></i> 保存设置</button>
                                            </td>
                                        </tr> -->
                                        <tr>
                                            <td colspan="6">
                                                <span class="pull-left">共 <span class="text-danger">{{ page.total_items }}</span> 条记录，当前页码：<span class="text-info">{{ page.current }}</span> / {{ max(1, page.total_pages) }}</span>
                                                <ul class="pagination pagination-sm no-margin pull-right" data-toggle="ajax" data-target="#ajax-content">{{ partial('partial/pagination', ['page': page]) }}</ul>
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