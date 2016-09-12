<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="phpBinPath">PHP可执行文件路径</label>
    <div class="col-md-5">
        {{ form.render('phpBinPath', ['class': 'form-control mono', 'placeholder': '默认为：/usr/bin/env php', 'data-tip': '请保证当前用户有可执行权限']) }}
    </div>
    <div class="col-md-7 msg-box" for="phpBinPath"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="tmpDir">临时文件目录</label>
    <div class="col-md-5">
        {{ form.render('tmpDir', ['class': 'form-control mono', 'placeholder': '留空使用PHP配置', 'data-tip': '上传文件保存的临时文件目录']) }}
    </div>
    <div class="col-md-7 msg-box" for="tmpDir"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="uploadPath">上传文件路径</label>
    <div class="col-md-5">
        {{ form.render('uploadPath', ['class': 'form-control mono', 'placeholder': '例如：uploaded/', 'data-tip': '请以“/”结尾']) }}
    </div>
    <div class="col-md-7 msg-box" for="uploadPath"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="uploadUrl">上传文件URL</label>
    <div class="col-md-5">
        {{ form.render('uploadUrl', ['class': 'form-control mono', 'placeholder': '例如：/uploaded/', 'data-tip': '末尾请加上“/”']) }}
    </div>
    <div class="col-md-7 msg-box" for="uploadUrl"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="uploadDirRule">上传目录规则</label>
    <div class="col-md-5">
        {{ form.render('uploadDirRule', ['class': 'form-control mono', 'placeholder': '例如：Y/m-d/', 'data-tip': '文件夹结尾需要加上“/”']) }}
    </div>
    <div class="col-md-7 msg-box" for="uploadDirRule"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="uploadSizeLimit">上传文件大小限制</label>
    <div class="col-md-5">
        {{ form.render('uploadSizeLimit', ['class': 'form-control mono', 'placeholder': '0为不限制', 'data-tip': '0为不限制，单位为字节']) }}
    </div>
    <div class="col-md-7 msg-box" for="uploadSizeLimit"></div>
</div>

<div class="form-group clearfix">
    <label class="col-md-12 control-label" for="uploadAllowExtensions">允许上传的文件格式</label>
    <div class="col-md-5">
        {{ form.render('uploadAllowExtensions', ['class': 'form-control mono', 'placeholder': '以“|”为分隔', 'data-tip': '例如：jpg|gif|png|jpeg']) }}
    </div>
    <div class="col-md-7 msg-box" for="uploadAllowExtensions"></div>
</div>
