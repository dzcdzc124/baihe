<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">{{ model.isNewRecord() ? '新增' : '更新' }}红包</h4>
</div>
<div class="modal-body">
    <div class="row">
        <form class="form-horizontal" role="form" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
            <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">

            <div class="form-group">
                <label class="col-md-3 control-label">金额范围(分)</label>
                <div class="col-md-7">
                    {{ form.render('minimum', ['class': 'form-control mono pull-left', 'style': 'width:45%;']) }}
                    <span class="text-center pull-left" style="width:10%;line-height:34px;">~</span>
                    {{ form.render('maximum', ['class': 'form-control mono pull-left', 'style': 'width:45%;']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">数量</label>
                <div class="col-md-7">
                    {{ form.render('total', ['class': 'form-control mono']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">权重</label>
                <div class="col-md-7">
                    {{ form.render('weight', ['class': 'form-control mono']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">排序</label>
                <div class="col-md-7">
                    {{ form.render('sort', ['class': 'form-control mono']) }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-primary">保存设置</button>
                </div>
            </div>
        </form>
    </div>
</div>