<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardBookmark extends Model
{
    protected $fillable = [
        'target_table',
        'target_id',
        'user_id',
        'ip',
    ];

    protected $hidden = [];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function write(): BelongsTo
    {
        return $this->belongsTo(BoardWrite::class, 'target_id', 'id');
    }
}
