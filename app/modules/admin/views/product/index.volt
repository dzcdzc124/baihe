{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/product']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            产品设置
            <small>Product</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['产品设置']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th class="col-md-5">产品名称</th>
                                            <th class="col-md-5">附加描述</th>
                                            <th>价格(分)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in productList %}
                                        <tr>
                                            <td>{{ item.id }}</td>
                                            <td>
                                                <input type="text" name="names[{{ item.id }}]" value="{{ item.name }}" class="form-control mono" tabindex="{{ item.id * 3 }}">
                                            </td>
                                            <td>
                                                <input type="text" name="details[{{ item.id }}]" value="{{ item.detail }}" class="text-right form-control mono" tabindex="{{ item.id * 3 + 1 }}">
                                            </td>
                                            <td>
                                                <input type="text" name="total_fees[{{ item.id }}]" value="{{ item.total_fee }}" class="text-right form-control mono" tabindex="{{ item.id * 3 + 2 }}">
                                            </td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <!-- <a href="#" data-remote="{{ url('/admin/question/create/') }}" data-trigger="dialog" class="btn btn-sm btn-default pull-left"><i class="glyphicon glyphicon-plus"></i> 新增产品</a> -->

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