{% extends "layouts/main.volt" %}

{% block content %}
{{ partial('partial/header', ['current_nav': 'index/lucky']) }}

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            中奖名单
            <small>Lucky Guy</small>
        </h1>
        {{ partial('partial/breadcrumbs', ['breadcrumbs' : [
            ['中奖名单']
        ]]) }}
    </section>

    <!-- Main content -->
    <section id="ajax-content" class="content">
        <!-- AJAX_CONTENT_START -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    {{ partial('partial/nav', [
                        'title': '中奖名单',
                        'navs': [
                            ['type.1', '实物奖', url(request.relativeURI(), ['type': 1]), '#ajax-content'],
                            ['type.2', '红包', url(request.relativeURI(), ['type': 2]), '#ajax-content'],
                            ['type.3', '虚拟卡', url(request.relativeURI(), ['type': 3]), '#ajax-content']
                        ],
                        'current_nav': type is not empty ? 'type.'~type : 'type.1'
                    ]) }}

                    <div class="tab-content no-padding">
                        <div class="tab-pane clearfix active">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- AJAX_CONTENT_END -->
    </section>
</div>
{% endblock %}