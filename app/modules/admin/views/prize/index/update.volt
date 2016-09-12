<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">{{ model.isNewRecord() ? '新增' : '更新' }}奖品</h4>
</div>
<div class="modal-body">
    <div class="row">
        <form id="prize-edit-form" class="form-horizontal" role="form" method="post" action="{{ request.getURI() }}" data-form="ajax" data-tips="toast" data-target="#ajax-content">
            <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">

            <div class="form-group">
                <label class="col-md-3 control-label">名称</label>
                <div class="col-md-7">
                    {{ form.render('name', ['class': 'form-control mono', 'tabindex': 1]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">类型</label>
                <div class="col-md-7">
                    {{ form.render('type', ['class': 'form-control mono', 'tabindex': 1]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">数量</label>
                <div class="col-md-7">
                    {{ form.render('total', ['class': 'form-control mono', 'tabindex': 2]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">中奖权重</label>
                <div class="col-md-7">
                    {{ form.render('weight', ['class': 'form-control mono', 'tabindex': 3]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">排序</label>
                <div class="col-md-7">
                    {{ form.render('sort', ['class': 'form-control mono', 'tabindex': 4]) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">中奖提示</label>
                <div class="col-md-7">
                    {{ form.render('message', ['class': 'form-control mono', 'tabindex': 5]) }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <div class="checkbox" style="min-height:0;padding-top:0;">
                        <label>{{ form.render('default') }} 默认奖品</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-primary">保存奖品</button>
                </div>
            </div>
        </form>
    </div>
</div>