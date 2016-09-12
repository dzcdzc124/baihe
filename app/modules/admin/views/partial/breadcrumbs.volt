{%- macro render_breadcrumb(breadcrumbs) -%}
<ol class="breadcrumb">
    <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> 后台首页</a></li>
    {%- for breadcrumb in breadcrumbs -%}
        {%- if loop.last -%}
            <li class="active">{{ breadcrumb[0] }}</li>
        {%- else -%}
            <li><a href="{{ breadcrumb[1] ? breadcrumb[1] : '#' }}"{% if not breadcrumb[0] %} onclick="javascript: return false;"{% endif %}>{{ breadcrumb[0] }}</a></li>
        {%- endif -%}
    {%- endfor -%}
</ol>
{%- endmacro -%}
{{ render_breadcrumb(breadcrumbs) }}