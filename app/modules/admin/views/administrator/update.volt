<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">{{ model.isNewRecord() ? '创建' : '更新' }}管理员</h4>
</div>
<div class="modal-body">
    <div class="row">
        <form id="administrator-edit-form" class="form-horizontal" role="form" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
            <div class="form-group">
                <label class="col-md-3 control-label" for="username">用户名</label>
                <div class="col-md-7">
                    {% if model.isNewRecord() %}
                        {{ form.render('username', ['class': 'form-control mono']) }}
                    {% else %}
                        <input type="text" name="username" class="form-control mono" value="{{ model.username }}" disabled="disabled" readonly="readonly">
                    {% endif %}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label" for="newPassword">密码</label>
                <div class="col-md-7">
                    {{ form.render('newPassword', ['class': 'form-control mono']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label" for="email">Email</label>
                <div class="col-md-7">
                    {{ form.render('email', ['class': 'form-control mono']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label" for="mobile">手机号码</label>
                <div class="col-md-7">
                    {{ form.render('mobile', ['class': 'form-control mono']) }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <div class="checkbox">
                        <label>{% if form.has('sa') %}{{ form.render('sa') }}{% else %}<input type="checkbox" disabled="disabled" readonly="readonly" {{ model.sa ? 'checked="checked"' : ''}}>{% endif %} 超级管理员</label>
                        <label style="margin-left:20px;">{% if form.has('activated') %}{{ form.render('activated') }}{% else %}<input type="checkbox" disabled="disabled" readonly="readonly" {{ model.activated ? 'checked="checked"' : ''}}>{% endif %} 启用</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-primary">保存数据</button>
                </div>
            </div>
        </form>
    </div>
</div>