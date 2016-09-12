{%- macro render_menu_item(menu, item, level) -%}
    <li class="{% if menu.current(level, item['id']) %}active{% if item['children'] is not empty %} expand{% endif %}{% endif %} {% if item['children'] is not empty %}{{ level ? '' : 'treeview' }}{% endif %}">
        <a href="{{ menu.url(item) }}">
            <i class="fa fa-{{ item['icon'] ? item['icon'] : 'circle-o' }}"></i> <span>{{ item['label'] }}</span>{% if item['children'] is not empty %} <i class="fa fa-angle-left pull-right"></i>{% endif %}
        </a>
        {%- if item['children'] is not empty -%}
            <ul class="treeview-menu">
                {%- for subitem in item['children'] -%}
                    {{ render_menu_item(menu, subitem, level + 1) }}
                {%- endfor -%}
            </ul>
        {%- endif -%}
    </li>
{%- endmacro -%}

{%- macro render_topmenu_item(menu, item, level) -%}
    {%- if item == 'separator' -%}
        <li role="separator" class="divider"></li>
    {%- elseif 'header' in item -%}
        <li class="header">{{ item['header'] }}</li>
    {%- else -%}
        {%- if level == 0 and menu.current(level, item['id']) -%}
            {%- set __ = menu.sub(item['children'] is not empty ? item['children'] : []) -%}
        {%- endif -%}

        <li class="{% if menu.current(level, item['id']) %}active{% endif %}">
            <a href="{{ menu.url(item) }}" title="{{ item['label'] is defined ? item['label'] : '' }}">
                <i class="fa fa-fw fa-{{ item['icon'] is not empty ? item['icon'] : 'circle-o' }}"></i>
                {%- if item['label'] is not empty -%}&nbsp;<span>{{ item['label'] }}</span>{%- endif -%}
            </a>
        </li>
    {%- endif -%}
{%- endmacro -%}

{%- set __ = menu.setCurrent(current_nav is not empty ? current_nav : null) -%}
<header class="main-header">
    <a href="{{ url('/admin/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Adm</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Admin Panel</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {%- set menuTree = menu.tree() -%}
                {%- if menuTree is not empty -%}
                    {%- for item in menuTree -%}
                        {{ render_topmenu_item(menu, item, 0) }}
                    {%- endfor -%}
                {%- endif -%}
                {{ partial("partial/usermenu") }}
            </ul>
        </div>
    </nav>
</header>

<aside class="main-sidebar">
    <!-- Sidebar account panel -->
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">导航菜单</li>
            {%- set subMenu = menu.sub() -%}
            {%- if subMenu is not empty -%}
                {%- for item in subMenu -%}
                    {{ render_menu_item(menu, item, 1) }}
                {%- endfor -%}
            {%- endif -%}
        </ul>
    </section>
</aside>