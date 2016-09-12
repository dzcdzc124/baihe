<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="sendName">商户名称</label>
    <div class="col-md-5">
        {{ form.render('sendName', ['class': 'form-control mono', 'data-tip': '红包上显示的商户名称']) }}
    </div>
    <div class="col-md-7 msg-box" for="sendName"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="actName">活动名称</label>
    <div class="col-md-5">
        {{ form.render('actName', ['class': 'form-control mono', 'data-tip': '活动名称，显示在服务通知里']) }}
    </div>
    <div class="col-md-7 msg-box" for="actName"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="remark">备注</label>
    <div class="col-md-12">
        {{ form.render('remark', ['class': 'form-control mono']) }}
    </div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="wishing">祝福语</label>
    <div class="col-md-12">
        {{ form.render('wishing', ['class': 'form-control mono', 'style': 'height:90px;']) }}
    </div>
</div>