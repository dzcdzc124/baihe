{% macro render_tab(title, navs, current_nav, buttons = null) %}
{%- set navs = array_reverse(navs) -%}
<ul class="nav nav-tabs pull-right">
    {%- for n in navs -%}
        {%- if is_string(n) -%}
            <li>{{ n }}</li>
        {%- elseif n -%}
            <li class="{{ n[0] == current_nav ? 'active' : '' }}">
                {%- if n[3] is defined -%}
                    <a href="#" data-remote="{{ n[2] }}" data-target="{{ n[3] }}" data-trigger="ajaxLoad">{{ n[1] }}</a>
                {%- else -%}
                    <a href="{{ n[2] }}">{{ n[1] }}</a>
                {%- endif -%}
            </li>
        {%- endif -%}
    {%- endfor -%}

    <li class="pull-left header" style="font-size:18px;">
        {{ title }}

        {%- if buttons -%}
            <small class="btn-extra">
                {%- if is_string(buttons) -%}
                    {{ buttons }}
                {%- else -%}
                    {%- for btn in buttons -%}
                        {%- if btn -%}
                            {{ html_a(btn) }}
                        {%- endif -%}
                    {%- endfor -%}
                {%- endif -%}
            </small>
        {%- endif -%}
    </li>
</ul>
{% endmacro %}
{{ render_tab(title, navs, current_nav is not empty ? current_nav : 'index', buttons is defined ? buttons : null) }}