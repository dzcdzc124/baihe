<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">{{ model.isNewRecord() ? '新增' : '更新' }}问题</h4>
</div>
<div class="modal-body">
    <div class="row">
        <form id="question-edit-form" class="form-horizontal" role="form" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
            <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">

            <div class="form-group">
                <label class="col-md-3 control-label">问题</label>
                <div class="col-md-7">
                    {{ form.render('question', ['class': 'form-control mono', 'tabindex': 1]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">题号</label>
                <div class="col-md-7">
                    {{ form.render('sort', ['class': 'form-control mono', 'tabindex': 1]) }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <div class="checkbox" style="min-height:0;padding-top:0;">
                        <label>{{ form.render('reverse') }} 是否反向计分</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-primary">保存问题</button>
                </div>
            </div>
        </form>
    </div>
</div>