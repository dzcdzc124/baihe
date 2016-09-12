<?php

namespace App\Models;


class Attachment extends ModelBase
{
    public $id;

    public $userId = 0;

    public $userType = 'user';

    public $path;

    public $thumbPath;

    public $name;

    public $ext;

    public $size = 0;

    public $mimetype;

    public $isImage = 0;

    public $width = 0;

    public $height = 0;

    public $weiboPicId;

    public $created;

    public $updated;
}