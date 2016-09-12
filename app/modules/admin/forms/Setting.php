<?php

namespace App\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Regex;

use App\Lib\Forms\Form;
use App\Lib\Forms\Element\CheckList;
use App\Lib\Forms\Element\RadioList;


class Setting extends Form
{
    public function initialize($entity, $userOptions)
    {
        $config = $this->getDI()->getShared('config');
        $setting = $this->getDI()->getShared('setting');

        /** 活动 **/
        $machines = new Text('machines', [
            'value' => $setting['machines'],
        ]);
        $this->add($machines);

        $smsTmpl = new TextArea('smsTmpl');
        $smsTmpl->setDefault($setting['smsTmpl']);
        $this->add($smsTmpl);

        /** 红包 **/
        $sendName = new Text('sendName', [
            'value' => $setting['sendName'],
        ]);
        $this->add($sendName);

        $actName = new Text('actName', [
            'value' => $setting['actName'],
        ]);
        $this->add($actName);

        $remark = new Text('remark', [
            'value' => $setting['remark'],
        ]);
        $this->add($remark);

        $wishing = new TextArea('wishing');
        $wishing->setDefault($setting['wishing']);
        $this->add($wishing);

        /** 基本 **/
        $phpBinPath = new Text('phpBinPath', [
            'value' => $setting['phpBinPath'],
        ]);
        $this->add($phpBinPath);
        
        $tmpDir = new Text('tmpDir', [
            'value' => $setting['tmpDir'],
        ]);
        $this->add($tmpDir);

        $uploadPath = new Text('uploadPath', [
            'value' => $setting['uploadPath'],
        ]);
        $this->add($uploadPath);

        $uploadUrl = new Text('uploadUrl', [
            'value' => $setting['uploadUrl'],
        ]);
        $this->add($uploadUrl);

        $uploadDirRule = new Text('uploadDirRule', [
            'value' => $setting['uploadDirRule'],
        ]);
        $this->add($uploadDirRule);

        $uploadSizeLimit = new Numeric('uploadSizeLimit', [
            'value' => $setting->getInt('uploadSizeLimit', 0),
        ]);
        $uploadSizeLimit->addValidator(new PresenceOf([
            'message' => '上传文件大小限制不能为空',
        ]))->addValidator(new Between([
            'minimum' => 0,
            'maximum' => 1000000000,
            'message' => '上传文件大小限制必需大于等于0',
        ]))->addFilter('int!');
        $this->add($uploadSizeLimit);

        $uploadAllowExtensions = new Text('uploadAllowExtensions', [
            'value' => $setting['uploadAllowExtensions'],
        ]);
        $this->add($uploadAllowExtensions);
    }
}