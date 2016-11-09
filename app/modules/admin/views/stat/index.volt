{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/stat']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            统计
            <small>Stat</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['统计']
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
                                            <th class="col-md-4">结果</th>
                                            <th class="col-md-2">性别</th>
                                            <th class="col-md-2">数量</th>
                                            <!-- <th class="text-right" style="width:50px;">操作</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in stat %}
                                        <tr>
                                            <td>
                                                {{item.data}}
                                            </td>
                                            <td>
                                                {{ item.sex == '1'? '男' : '女'}}
                                            </td>
                                            <td>
                                                {{item.total}}
                                            </td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        
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