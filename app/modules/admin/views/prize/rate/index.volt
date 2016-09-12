{% extends "layouts/main.volt" %}

{% block header %}
<script src="{{ static_url('components/input-mask/jquery.inputmask.js') }}" type="text/javascript"></script>
<script src="{{ static_url('components/input-mask/jquery.inputmask.date.extensions.js') }}" type="text/javascript"></script>
{% endblock %}

{% block content %}
{%- macro render_item(item, prefix, prizeList) -%}
    <tr id="rate-item-{{ prefix }}">
        <td>
            <input type="text" name="data[{{ prefix }}][dateStr]" value="{{ item ? item.dateStr : '' }}" class="form-control mono" data-mask="date" style="width:150px;">
        </td>
        {% for prize in prizeList %}
            <td>
                <input type="text" name="data[{{ prefix }}][data][prizeId_{{ prize.id }}]" value="{{ item ? item.getData('prizeId_'~prize.id) : 0 }}" class="form-control mono text-right">
            </td>
        {% endfor %}
        <td class="text-right">
            <a href="#" class="rate-del" data-target="#rate-item-{{ prefix }}"{% if item %} data-remote="{{ url('/admin/prize/rate/delete/', ['date': item.dateStr]) }}"{% endif %}><i class="glyphicon glyphicon-remove text-danger"></i></a>
        </td>
    </tr>
{%- endmacro -%}

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
                    {{ partial('prize/nav', ['current_nav': 'rate']) }}

                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                            <form class="form-horizontal" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
                                <table class="table table-hover table-form-control no-margin">
                                    <thead>
                                        <tr>
                                            <th>日期</th>
                                            {% for item in prizeList %}
                                                <th class="text-right" style="width:120px;">{{ item.name }}</th>
                                            {% endfor %}
                                            <th class="text-right" style="width:50px;">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rate-list">
                                        {% for item in page.items %}
                                            {{ render_item(item, item.dateStr, prizeList) }}
                                        {% endfor %}
                                        {{ render_item(false, 100, prizeList) }}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="{{ count(prizeList) + 2 }}">
                                                <div class="pull-left">
                                                    <a href="#" class="btn btn-sm btn-default rate-add" data-target="#rate-list"><i class="glyphicon glyphicon-plus"></i> 增加</a>

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
<script id="rateTmpl" type="text/template">
    {{ render_item(false, '<%= prefix %>', prizeList) }}
</script>
<script type="text/javascript">
$(function(){
    var currentPrefix = 100,
        $rateTmpl = _.template($('#rateTmpl').html());

    function render_datetime() {
        $('[data-mask="date"]').inputmask({
            mask: "y-1-2",
            placeholder: "yyyy-mm-dd",
            separator: '-',
            alias: "yyyy/mm/dd",
            hourFormat: "24"
        });

        $('[data-mask="datetime"]').inputmask({
            mask: "y-1-2 h:s:s",
            placeholder: "yyyy-mm-dd hh:mm:ss",
            separator: '-',
            alias: "yyyy/mm/dd",
            hourFormat: "24"
        });
    }

    $(document).on('click', '.rate-add', function(e){
        e.preventDefault();

        var me = $(this),
            target = me.data('target');

        currentPrefix = currentPrefix + 1;
        var html = $rateTmpl({
            prefix: currentPrefix
        });
        $(target).append(html);
        render_datetime();
    }).on('click', '.rate-del', function(e){
        e.preventDefault();

        var me = $(this),
            target = me.data('target'),
            remote = me.data('remote');

        if (remote) {
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
            })
        } else {
            $(target).remove();
        }
    });

    render_datetime();
});
</script>
{% endblock %}