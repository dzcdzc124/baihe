{%- macro render_pagination(page, baseUrl = null, target = null) -%}
{%- set first = max(page.first, 1) -%}
{%- set last = max(page.last, 1) -%}
{%- set start = max(first + 1, page.current - 4) -%}
{%- set end = min(last - 1, page.current + 5) -%}
{%- set baseUrl = baseUrl ? baseUrl : request.relativeURI() -%}
{%- set target = target ? target : '#ajax-content' -%}

{%- if page.before <= 0 or page.before == page.current -%}
    <li class="previous disabled">
        <a href="#" onclick="javascript:return false;">«</a>
    </li>
{%- else -%}
    <li class="previous">
        <a href="{{ url(baseUrl, ['page': page.before]) }}" data-trigger="ajaxLoad" data-target="{{ target }}">«</a>
    </li>
{%- endif -%}

{%- if page.current == first -%}
    <li class="active">
        <a href="#" onclick="javascript:return false;">{{ first }}</a>
    </li>
{%- else -%}
    <li>
        <a href="{{ url(baseUrl, ['page': first]) }}" data-trigger="ajaxLoad" data-target="{{ target }}">{{ first }}</a>
    </li>
{%- endif -%}

{%- if last > first -%}
    {%- if start > first + 1 -%}
    <li class="disabled"><span class=ellipsis>…</span></li>
    {%- endif -%}

    {%- if end >= start -%}
        {%- for p in range(start, end) -%}
            {%- if page.current == p -%}
                <li class="active">
                    <a href="#" onclick="javascript:return false;">{{ p }}</a>
                </li>
            {%- else -%}
                <li>
                    <a href="{{ url(baseUrl, ['page': p]) }}" data-trigger="ajaxLoad" data-target="{{ target }}">{{ p }}</a>
                </li>
            {%- endif -%}
        {%- endfor -%}
    {%- endif -%}

    {%- if end < last - 1 -%}
    <li class="disabled"><span class="ellipsis">…</span></li>
    {%- endif -%}

    {%- if page.current == last -%}
        <li class="active">
            <a href="#" onclick="javascript:return false;">{{ last }}</a>
        </li>
    {%- else -%}
        <li>
            <a href="{{ url(baseUrl, ['page': last]) }}" data-trigger="ajaxLoad" data-target="{{ target }}">{{ last }}</a>
        </li>
    {%- endif -%}
{%- endif -%}

{%- if page.next <= 0 or page.next == page.current -%}
    <li class="next disabled">
        <a href="#" onclick="javascript:return false;">»</a>
    </li>
{%- else -%}
    <li class="next">
        <a href="{{ url(baseUrl, ['page': page.next]) }}" data-trigger="ajaxLoad" data-target="#ajax-content">»</a>
    </li>
{%- endif -%}
{%- endmacro -%}
{{- render_pagination(page, baseUrl is defined ? baseUrl : null, target is defined ? target : '#ajax-content') -}}