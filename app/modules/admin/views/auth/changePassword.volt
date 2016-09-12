<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="exampleModalLabel">修改密码</h4>
</div>
<div class="modal-body">
    <div class="row">
        <form class="form-horizontal" role="form" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast">
            <div class="form-group">
                <label class="col-md-3 control-label">当前密码</label>
                <div class="col-md-7">
                    <input type="password" name="origin_password" id="origin_password" class="form-control mono" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">新密码</label>
                <div class="col-md-7">
                    <input type="password" name="new_password" id="new_password" class="form-control mono" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">确认新密码</label>
                <div class="col-md-7">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control mono" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <input type="submit" class="btn btn-primary" value="修改密码">
                </div>
            </div>
        </form>
    </div>
</div>