{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/prize']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            奖品设置
            <small>Prize</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['奖品设置']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('prize/nav', ['current_nav': 'redpacket']) }}

                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th>金额范围(元)</th>
                                            <th class="text-right" style="width:150px;">已发总额</th>
                                            <th class="text-right" style="width:150px;">已发数量</th>
                                            <th class="text-right" style="width:150px;">总数量</th>
                                            <th class="text-right" style="width:150px;">权重</th>
                                            <th class="text-right" style="width:50px;">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in redpacketList %}
                                        <tr>
                                            <td>
                                                {% if item.maximum == item.minimum %}
                                                    {{ format_money(item.minimum) }}
                                                {% else %}
                                                    {{ format_money(item.minimum) }} ~ {{ format_money(item.maximum) }}
                                                {% endif %}
                                            </td>
                                            <td class="text-right">{{ format_money(item.amount) }}</td>
                                            <td class="text-right">{{ item.used }}</td>
                                            <td class="text-right">
                                                <input type="text" name="totals[{{ item.id }}]" value="{{ item.total }}" class="text-right pull-right form-control mono" style="width:120px;" tabindex="{{ item.id * 2 }}">
                                            </td>
                                            <td class="text-right">
                                                <input type="text" name="weights[{{ item.id }}]" value="{{ item.weight }}" class="text-right pull-right form-control mono" style="width:120px;" tabindex="{{ item.id * 2 + 1 }}">
                                            </td>
                                            <td class="text-right">
                                                <a href="#" data-remote="{{ url('/admin/prize/redpacket/update/', ['id': item.id]) }}" data-trigger="dialog"><i class="glyphicon glyphicon-pencil"></i></a>
                                            </td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <a href="#" data-remote="{{ url('/admin/prize/redpacket/create/') }}" data-trigger="dialog" class="btn btn-sm btn-default pull-left"><i class="glyphicon glyphicon-plus"></i> 新增</a>

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