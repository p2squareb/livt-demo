<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Board extends Model
{
    protected $fillable = [
        'table_id',
        'table_name',
        'is_open',
        'use_category',
        'category_list',
        'write_level',
        'use_comment',
        'use_file_upload',
        'use_like',
        'use_report',
        'skin',
    ];

    protected $hidden = [];

    protected $guarded = [
        'article_count',
        'comment_count',
    ];
}
