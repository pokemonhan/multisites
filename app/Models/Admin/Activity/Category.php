<?php

namespace App\Models\Admin\Activity;

use App\Models\BaseModel;

class Category extends BaseModel
{
    protected $table = 'partner_category';

    protected $fillable = [
        'title', 'parent', 'template', 'platform_id', 'created_at', 'updated_at',
    ];
}