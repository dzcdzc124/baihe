<?php

namespace App\Modules\Admin\Controllers;

use App\Helpers\Upload as UploadHelper;


class AttachmentController extends ControllerBase
{
    public function uploadAction()
    {
        if ( ! $this->request->isPost())
            $this->serveJson('非法请求');

        try {
            $attachment = UploadHelper::save($this->currentUser, 'upload_file');
        } catch (\Exception $e) {
            $this->serveJson($e->getMessage());
        }

        $this->serveJson('上传成功', 0, [
            'attachmentId' => $attachment->id,
            'name' => $attachment->name,
            'size' => $attachment->size,
            'url' => UploadHelper::url($attachment->path),
            'thumbUrl' => UploadHelper::url($attachment->thumbPath),
        ]);
    }

    public function tmpAction()
    {
        if ( ! $this->request->isPost())
            $this->serveJson('非法请求');

        try {
            $res = UploadHelper::save($this->currentUser, 'upload_file', ['xls', 'xlsx'], true);
            $id = $res['id'];

            $this->cache->save('tmpfile_' . $id, $res, 600);
        } catch (\Exception $e) {
            $this->serveJson($e->getMessage());
        }

        $this->serveJson('上传成功', 0, [
            'id' => $id,
            'name' => $res['filename'],
            'size' => $res['size'],
        ]);
    }
}