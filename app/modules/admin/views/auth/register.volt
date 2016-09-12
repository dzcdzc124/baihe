{% if count >= 1 %}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">温馨提示...</h4>
</div>
<div class="modal-body">
    <div class="well text-danger text-center no-margin">
        <p class="no-margin">系统已存在管理员帐户，请使用管理员帐户登录后，在管理后台新增管理员。</p>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">确定并关闭</button>
</div>
{% else %}
<form id="register-form" class="form-horizontal" role="form" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toastr">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">注册新帐户</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="form-group">
                <label class="col-md-3 control-label">用户名</label>
                <div class="col-md-7">
                    {{ form.render('username', ['class': 'form-control mono', 'tabindex': 1]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">密码</label>
                <div class="col-md-7">
                    {{ form.render('password', ['class': 'form-control mono', 'tabindex': 2]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">确认密码</label>
                <div class="col-md-7">
                    {{ form.render('passwordAgain', ['class': 'form-control mono', 'tabindex': 3]) }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary">注册</button>
    </div>
</form>

<script type="text/javascript">
$(function(){
    $('#register-form').on('ajax:success', function(e, data){
        if (data.errcode == 0) {
            $._modal.modal('hide');
        }
    });
});
</script>
{% endif %}