<!-- User Account -->
<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{ static_url('img/avatar.png') }}" class="user-image" alt="User Image" />
        <span class="hidden-xs">{{ identity.username }}</span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <img src="{{ static_url('img/avatar.png') }}" class="img-circle" alt="User Image" />
            <p>
                {{ identity.username }}
                <small>管理员</small>
            </p>
        </li>
        <!-- Menu Body -->
        <!-- <li class="user-body">
            <div class="col-xs-4 text-center">
                <a href="#">Followers</a>
            </div>
            <div class="col-xs-4 text-center">
                <a href="#">Sales</a>
            </div>
            <div class="col-xs-4 text-center">
                <a href="#">Friends</a>
            </div>
        </li> -->
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="#" data-remote="{{ url('/admin/auth/changePassword/') }}" data-trigger="dialog" class="btn btn-default btn-flat">修改密码</a>
            </div>
            <div class="pull-right">
                <a href="{{ url('/admin/auth/logout/') }}" class="btn btn-default btn-flat">退出登录</a>
            </div>
        </li>
    </ul>
</li>
