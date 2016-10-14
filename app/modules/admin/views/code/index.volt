{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/code']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            兑换码
            <small>IMEI</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['兑换码']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('partial/nav', [
                        'title': '兑换码',
                        'navs': [
                            ['index', '全部', url('/admin/code/')],
                            ['given-0', '未给出', url('/admin/code/',['given': 0])],
                            ['status-0', '未使用', url('/admin/code/',['status': 0])],
                            ['status-1', '已使用', url('/admin/code/',['status': 1])]
                        ],
                        'current_nav': status == '0' or status == '1' ? 'status-'~status : (given == '0' ? 'given-'~given : 'index')
                    ]) }}

                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover no-margin">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th style="width:300px;">兑换码</th>
                                            <th style="width:100px;">是否给出</th>
                                            <th style="width:100px;">是否使用</th>
                                            <th style="width:200px;">使用时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in page.items %}
                                            <tr>
                                                <td>{{ item.id }}</td>
                                                <td>{{ item.code }}</td>
                                                <td>
                                                    {% if item.status == 0 %}
                                                    <input type="hidden" name="givens[{{ item.id }}]" value="0">
                                                    <!-- <input type="checkbox" name="reverses[{{ item.id }}]" value="1">  -->
                                                    <input type="checkbox" name="givens[{{ item.id }}]" value="1" {{ item.given?'checked':'' }} > 
                                                    {%endif%}
                                                </td>  
                                                <td>{{ item.status ? '已使用' : '未使用' }}</td>
                                                <td class="text-right">{{  item.status ? date('Y-m-d H:i:s', item.updated) : '' }}</td>
                                            </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <a href="{{ url('/admin/code/create/') }}" class="btn btn-sm btn-default rate-add" data-target="#rate-list"><i class="glyphicon glyphicon-plus"></i> 新增{{createNum}}个兑换码</a>
                                                <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-floppy-disk"></i> 保存设置</button>
                                            </td>
                                        </tr>
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