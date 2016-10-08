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
                            ['payed', '已付订单', url('/admin/order/payed/')]
                        ],
                        'current_nav': current_nav is not empty ? current_nav : 'index'
                    ]) }}
                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th class="col-md-2">奖品名称</th>
                                            <th style="width:80px;">类型</th>
                                            <th>中奖提示</th>
                                            <th class="text-right">已发放</th>
                                            <th class="text-right" style="width:150px;">数量</th>
                                            <th class="text-right" style="width:150px;">中奖权重</th>
                                            <th class="text-right" style="width:50px;">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in orderList %}
                                        <tr>
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.typeLabel }}</td>
                                            <td>{{ item.message|default(item.type ? '恭喜你获得'~item.name : '很遗憾，你没有中奖') }}</td>
                                            <td class="text-right">{{ item.used }}</td>
                                            <td class="text-right">
                                                <input type="text" name="totals[{{ item.id }}]" value="{{ item.total }}" class="text-right pull-right form-control mono" style="width:120px;" tabindex="{{ item.id * 2 }}">
                                            </td>
                                            <td class="text-right">
                                                <input type="text" name="weights[{{ item.id }}]" value="{{ item.weight }}" class="text-right pull-right form-control mono" style="width:120px;" tabindex="{{ item.id * 2 + 1 }}">
                                            </td>
                                            <td class="text-right">
                                                <a href="#" data-remote="{{ url('/admin/order/index/update/', ['id': item.id]) }}" data-trigger="dialog"><i class="glyphicon glyphicon-pencil"></i></a>
                                            </td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <a href="#" data-remote="{{ url('/admin/order/index/create/') }}" data-trigger="dialog" class="btn btn-sm btn-default pull-left"><i class="glyphicon glyphicon-plus"></i> 新增奖品</a>

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