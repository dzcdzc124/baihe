{% extends "layouts/main.volt" %}

{% block header %}
<script src="{{ static_url('components/input-mask/jquery.inputmask.js') }}" type="text/javascript"></script>
<script src="{{ static_url('components/input-mask/jquery.inputmask.date.extensions.js') }}" type="text/javascript"></script>
{% endblock %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'setting']) }}

{%- set tabs = array_reverse([
    'activity': '活动',
    'redpacket': '红包',
    'system': '系统'
]) -%}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            系统设置
            <small>Setting</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['系统设置']
        ]]) }}
    </section>
    
    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-8">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        {% for c, v in tabs %}
                            <li class="{{ loop.last ? 'active' : '' }}">
                                <a href="#tab-{{ c }}" data-toggle="tab">{{ v }}</a>
                            </li>
                        {% endfor %}

                        <li class="pull-left header" style="font-size:18px;">设置</li>
                    </ul>

                    <form role="form" method="post" action="{{ request.getURI() }}" data-form="validator" data-validator-option="{theme: 'simple'}" data-tips="toast">
                        <div class="tab-content" style="padding-top:15px;">
                            {% for c in array_keys(tabs) %}
                                <div id="tab-{{ c }}" class="tab-pane clearfix {{ loop.last ? 'active' : '' }}">
                                    {{ partial('setting/form_'~c) }}
                                </div>
                            {% endfor %}
                        </div>

                        <div class="box-footer clearfix">
                            <button type="submit" class="btn btn-primary">保存设置</button>
                        </div>
                    </form>
                </div>
            </div>

            {{ partial('setting/sidebar') }}
        </div>
        <!-- AJAX_CONTENT_END -->
    </section>
</div>
{% endblock %}

{% block footer %}
<script type="text/javascript">
$(function(){
    $("[data-mask=datetime]").inputmask({
        mask: "y-1-2 h:s:s",
        placeholder: "yyyy-mm-dd hh:mm:ss",
        separator: '-',
        alias: "yyyy/mm/dd",
        hourFormat: "24"
    });
});
</script>
{% endblock %}