<?php

namespace App\Models\Admin\Notice;

use App\Models\BaseModel;

class FrontendMessageNoticesContent extends BaseModel
{
    public const TYPE_NOTICE = 1;
    public const TYPE_MESSAGE = 2;

    protected $guarded = ['id'];

    // public function getPicPathAttribute()
    // {
    //     return Request::server("HTTP_HOST") . $this->attributes['pic_path'];
    // }
}
