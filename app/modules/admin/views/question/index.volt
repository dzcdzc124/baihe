{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/question']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            问题列表
            <small>Question</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['问题列表']
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
                                            <th class="col-md-8">问题</th>
                                            <th style="width:80px;">题号</th>
                                            <th>是否反向计分</th>
                                            <!-- <th class="text-right" style="width:50px;">操作</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% for item in questionList %}
                                        <tr>
                                            <td>{{ item.id }}</td>
                                            <td>
                                                <input type="text" name="questions[{{ item.id }}]" value="{{ item.question }}" class="pull-right form-control mono" tabindex="{{ item.id * 3 }}">
                                            </td>
                                            <td>
                                                <input type="text" name="sorts[{{ item.id }}]" value="{{ item.sort }}" class="text-right pull-right form-control mono" style="width:120px;" tabindex="{{ item.id * 3 + 1 }}">
                                            </td>
                                            <td>
                                                <input type="hidden" name="reverses[{{ item.id }}]" value="0">
                                                <!-- <input type="checkbox" name="reverses[{{ item.id }}]" value="1">  -->
                                                <input type="checkbox" name="reverses[{{ item.id }}]" value="1" {{ item.reverse?'checked':'' }} tabindex="{{ item.id * 3 + 2 }}"> 
                                            </td>
                                        </tr>
                                        {% endfor %}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8">
                                                <a href="#" data-remote="{{ url('/admin/question/create/') }}" data-trigger="dialog" class="btn btn-sm btn-default pull-left"><i class="glyphicon glyphicon-plus"></i> 新增问题</a>

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