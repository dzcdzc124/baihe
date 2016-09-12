<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="machines">手机型号范围</label>
    <div class="col-md-5">
        {{ form.render('machines', ['class': 'form-control mono', 'data-tip': '多个型号以英文逗号","区分']) }}
    </div>
    <div class="col-md-7 msg-box" for="machines"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="smsTmpl">短信模板（发送游戏礼包）</label>
    <div class="col-md-12">
        {{ form.render('smsTmpl', ['class': 'form-control mono', 'style': 'height:90px;']) }}
    </div>
</div>