{% extends "layouts/main.volt" %}

{% block header %}
<script src="{{ static_url('components/input-mask/jquery.inputmask.js') }}" type="text/javascript"></script>
<script src="{{ static_url('components/input-mask/jquery.inputmask.date.extensions.js') }}" type="text/javascript"></script>
{% endblock %}

{% block content %}
{%- macro render_item(item, prefix) -%}
    <tr id="district-item-{{ prefix }}">
        <td>
            {% if not item %}
                <span class="pull-left">-</span>
            {% else %}
                <strong class="text-info">{{ url('/'~item.id~'/') }}</strong>
            {% endif %}
        </td>
        <td>
            <input type="hidden" name="data[{{ prefix }}][id]" value="{{ item ? item.id : '' }}" class="form-control mono">
            <input type="text" name="data[{{ prefix }}][name]" value="{{ item ? item.name : '' }}" class="form-control mono">
        </td>
        <td class="text-right">
            <a href="#" class="district-del" data-target="#district-item-{{ prefix }}"{% if item %} data-remote="{{ url('/admin/district/delete/', ['id': item.id]) }}"{% endif %}><i class="glyphicon glyphicon-remove text-danger"></i></a>
        </td>
    </tr>
{%- endmacro -%}

{{ partial('partial/header', ['current_nav': 'index/district']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            区域设置
            <small>District</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['区域设置']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('partial/nav', [
                        'title': '区域设置',
                        'navs': [
                            ['index', '全部', url('/admin/district/')]
                        ],
                        'current_nav': 'index'
                    ]) }}

                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th>URL</th>
                                            <th class="col-md-2">区域名称</th>
                                            <th class="text-right" style="width:50px;">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody id="district-list">
                                        {% for item in page.items %}
                                            {{ render_item(item, item.id) }}
                                        {% endfor %}
                                        {{ render_item(false, 100) }}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <div class="pull-left">
                                                    <a href="#" class="btn btn-sm btn-default district-add" data-target="#district-list"><i class="glyphicon glyphicon-plus"></i> 增加</a>

                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> 保存设置</button>
                                                </div>

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

{% block footer %}
<script id="districtTmpl" type="text/template">
    {{ render_item(false, '<%= prefix %>') }}
</script>
<script type="text/javascript">
$(function(){
    var currentPrefix = 100,
        $districtTmpl = _.template($('#districtTmpl').html());

    $(document).on('click', '.district-add', function(e){
        e.preventDefault();

        var me = $(this),
            target = me.data('target');

        currentPrefix = currentPrefix + 1;
        var html = $districtTmpl({
            prefix: currentPrefix
        });
        $(target).append(html);
    }).on('click', '.district-del', function(e){
        e.preventDefault();

        var me = $(this),
            target = me.data('target'),
            remote = me.data('remote');

        if (remote) {
            $.confirm('确定要删除该区域吗？', function(ok){
                if (ok) {
                    $.ajax({
                        url: remote,
                        type: 'post',
                        success: function(data){
                            if (data.errcode == 0) {
                                $(target).remove();
                            } else {
                                toastr.error(data.errmsg);
                            }
                        }
                    });
                }
            });
        } else {
            $(target).remove();
        }
    });
});
</script>
{% endblock %}